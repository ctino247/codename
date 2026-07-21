<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Setting;
use App\Models\Document;
use App\Models\Payment;
use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeneficiaryDashboard extends Component
{
    use WithFileUploads;

    // Profile & Bank fields
    public string $name = '';
    public string $bank_name = '';
    public string $account_number = '';
    public string $account_name = '';
    public string $swift_code = '';
    public string $routing_number = '';

    // Document uploads
    public $national_id_file;
    public $passport_photo_file;
    public $proof_of_address_file;

    // Contract Acceptance
    public bool $agree_contract = false;

    // Feedback Messages
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->bank_name = $user->bank_name ?? '';
        $this->account_number = $user->account_number ?? '';
        $this->account_name = $user->account_name ?? '';
        $this->swift_code = $user->swift_code ?? '';
        $this->routing_number = $user->routing_number ?? '';

        $this->agree_contract = $user->contract_accepted_at !== null;
    }

    public function saveProfile(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|numeric|digits_between:10,15',
            'account_name' => 'required|string|max:255',
            'swift_code' => 'nullable|string|max:50',
            'routing_number' => 'nullable|string|max:50',
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $this->name,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'swift_code' => $this->swift_code,
            'routing_number' => $this->routing_number,
            'profile_completed_at' => now(),
        ]);

        ActivityLog::log('profile_completed', 'Updated profile and bank details', $user->id);

        $user->updateOnboardingStatus();

        $this->successMessage = 'Profile and Bank details updated successfully!';
        $this->dispatch('refreshDashboard');
    }

    public function uploadDocument(string $type): void
    {
        $fileVar = $type . '_file';

        if (!$this->$fileVar) {
            $this->errorMessage = 'Please select a file to upload.';
            return;
        }

        $this->validate([
            $fileVar => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ]);

        $user = auth()->user();
        $path = $this->$fileVar->store('documents', 'public');

        Document::updateOrCreate(
            [
                'user_id' => $user->id,
                'document_type' => $type,
            ],
            [
                'file_path' => $path,
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );

        ActivityLog::log('document_uploaded', 'Uploaded document: ' . Document::typeLabel($type), $user->id);

        $user->updateOnboardingStatus();

        $this->successMessage = Document::typeLabel($type) . ' uploaded successfully!';
        $this->$fileVar = null; // Reset file input
        $this->dispatch('refreshDashboard');
    }

    public function acceptContract(): void
    {
        $user = auth()->user();

        // Ensure checklist completed before accepting contract
        $user->updateOnboardingStatus();
        if ($user->status === 'pending_profile') {
            $this->errorMessage = 'Please complete your bank details and required document uploads before accepting the contract.';
            $this->agree_contract = false;
            return;
        }

        if ($this->agree_contract) {
            $user->update([
                'contract_accepted_at' => now(),
            ]);
            ActivityLog::log('contract_accepted', 'Accepted official program contract', $user->id);
            $this->successMessage = 'Contract accepted successfully!';
        } else {
            $user->update([
                'contract_accepted_at' => null,
            ]);
            ActivityLog::log('contract_revoked', 'Revoked contract acceptance', $user->id);
            $this->successMessage = 'Contract acceptance revoked.';
        }

        $user->updateOnboardingStatus();
        $this->dispatch('refreshDashboard');
    }

    public function render()
    {
        $user = auth()->user();

        // Make sure their onboarding status is completely accurate
        $user->updateOnboardingStatus();

        // 1. Get stats
        $requiredDocs = json_decode(Setting::get('required_documents', '[]'), true) ?: [];
        $uploadedDocs = $user->documents->pluck('status', 'document_type')->toArray();

        // 2. Referrals stats
        $minRefs = (int)Setting::get('min_referrals_required', 3);
        $referralsEnabled = Setting::get('referrals_required_enabled', '1') === '1';
        $verifiedRefs = $user->getVerifiedReferralsCount();
        $referralProgress = $minRefs > 0 ? min(100, round(($verifiedRefs / $minRefs) * 100)) : 100;

        // 3. Payroll countdown calculation
        $processingDay = (int)Setting::get('payroll_processing_date', 25);
        $now = now();
        $targetDate = now()->day($processingDay)->startOfDay();
        if ($now->day >= $processingDay) {
            $targetDate = $targetDate->copy()->addMonth();
        }
        $targetTimestamp = $targetDate->timestamp;

        // 4. Payroll Roadmap Data
        // Total payments received
        $payments = $user->payments()->orderBy('month_number')->get();
        $totalReceived = $payments->where('status', 'Paid')->count();
        $totalDisbursed = $payments->where('status', 'Paid')->sum('amount');

        $duration = (int)Setting::get('total_program_duration', 12);
        $monthlyAmount = (float)Setting::get('monthly_payroll_amount', 20000);
        $remainingPayments = max(0, $duration - $totalReceived);
        $remainingAmount = $remainingPayments * $monthlyAmount;

        $roadmap = [];
        for ($m = 1; $m <= $duration; $m++) {
            $pmt = $payments->where('month_number', $m)->first();
            if ($pmt) {
                $status = $pmt->status; // 'Paid', 'Pending', 'Skipped'
                $date = $pmt->payment_date ? $pmt->payment_date->format('d M Y') : null;
                $ref = $pmt->reference_number;
            } else {
                if ($user->status === 'active') {
                    // Show next un-created month as Pending, others as Upcoming
                    $isNext = ($m === ($totalReceived + 1));
                    $status = $isNext ? 'Pending' : 'Upcoming';
                } else {
                    $status = 'Locked';
                }
                $date = null;
                $ref = null;
            }

            $roadmap[] = [
                'month' => $m,
                'status' => $status,
                'date' => $date,
                'ref' => $ref,
                'amount' => $monthlyAmount,
            ];
        }

        // 5. Build Checklist
        $checklist = [
            'email_verified' => [
                'label' => 'Email Address Verified',
                'completed' => $user->email_verified_at !== null,
                'description' => 'Verify your email address to secure your account.',
            ],
            'profile_completed' => [
                'label' => 'Complete Profile & Bank Details',
                'completed' => !empty($user->bank_name) && !empty($user->account_number) && !empty($user->account_name),
                'description' => 'Provide a valid bank account to receive disbursements.',
            ],
        ];

        if (count($requiredDocs) > 0) {
            $completedDocs = $user->documents()->whereIn('document_type', $requiredDocs)->where('status', '!=', 'rejected')->count();
            $checklist['documents_uploaded'] = [
                'label' => 'Upload Required Documents (' . $completedDocs . '/' . count($requiredDocs) . ')',
                'completed' => $completedDocs >= count($requiredDocs),
                'description' => 'Upload ID proof, passport picture, and utilities for validation.',
            ];
        }

        if ($referralsEnabled) {
            $checklist['referrals_met'] = [
                'label' => 'Refer Minimum Required People (' . $verifiedRefs . '/' . $minRefs . ')',
                'completed' => $verifiedRefs >= $minRefs,
                'description' => 'Help our program grow by referring other verified applicants.',
            ];
        }

        $checklist['contract_accepted'] = [
            'label' => 'Read and Accept Official Contract',
            'completed' => $user->contract_accepted_at !== null,
            'description' => 'Download and agree to the formal program terms.',
        ];

        return view('livewire.beneficiary-dashboard', [
            'user' => $user,
            'checklist' => $checklist,
            'requiredDocs' => $requiredDocs,
            'uploadedDocs' => $uploadedDocs,
            'minRefs' => $minRefs,
            'referralsEnabled' => $referralsEnabled,
            'verifiedRefs' => $verifiedRefs,
            'referralProgress' => $referralProgress,
            'targetTimestamp' => $targetTimestamp,
            'roadmap' => $roadmap,
            'totalReceived' => $totalReceived,
            'totalDisbursed' => $totalDisbursed,
            'remainingPayments' => $remainingPayments,
            'remainingAmount' => $remainingAmount,
            'activityLogs' => $user->activityLogs()->take(5)->get(),
        ])->layout('layouts.app');
    }
}
