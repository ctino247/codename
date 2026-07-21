<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Setting;
use App\Models\Document;
use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminBeneficiaries extends Component
{
    use WithPagination;

    // Filters and Search
    public string $search = '';
    public string $filterStatus = ''; // '', 'pending_profile', 'pending_approval', 'active', 'suspended', 'rejected'
    public string $filterReferral = ''; // '', 'met', 'pending'
    public string $filterDate = ''; // '', 'today', 'week', 'month'

    // Form states
    public bool $manuallyAddMode = false;
    public bool $editMode = false;
    public bool $viewDocsMode = false;
    public ?int $selectedUserId = null;

    // Form inputs (Add/Edit)
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $bank_name = '';
    public string $account_number = '';
    public string $account_name = '';
    public string $swift_code = '';
    public string $routing_number = '';
    public string $user_status = 'pending_profile';

    // Document review input
    public ?int $selectedDocId = null;
    public string $admin_notes = '';

    // Alerts
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterReferral' => ['except' => ''],
        'filterDate' => ['except' => ''],
    ];

    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized administrative access.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterReferral = '';
        $this->filterDate = '';
        $this->resetPage();
    }

    // Modal / mode openers
    public function openAddModal(): void
    {
        $this->resetForm();
        $this->manuallyAddMode = true;
    }

    public function openEditModal(int $userId): void
    {
        $this->resetForm();
        $this->selectedUserId = $userId;
        $user = User::findOrFail($userId);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->bank_name = $user->bank_name ?? '';
        $this->account_number = $user->account_number ?? '';
        $this->account_name = $user->account_name ?? '';
        $this->swift_code = $user->swift_code ?? '';
        $this->routing_number = $user->routing_number ?? '';
        $this->user_status = $user->status;

        $this->editMode = true;
    }

    public function openDocsModal(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->viewDocsMode = true;
    }

    public function closeModal(): void
    {
        $this->manuallyAddMode = false;
        $this->editMode = false;
        $this->viewDocsMode = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->selectedUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->bank_name = '';
        $this->account_number = '';
        $this->account_name = '';
        $this->swift_code = '';
        $this->routing_number = '';
        $this->user_status = 'pending_profile';
        $this->selectedDocId = null;
        $this->admin_notes = '';
    }

    // Manually Create Beneficiary
    public function storeBeneficiary(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|numeric|digits_between:10,15',
            'account_name' => 'nullable|string|max:255',
        ]);

        // Generate unique referral code
        $referral_code = strtoupper(Str::random(10));
        while (User::where('referral_code', $referral_code)->exists()) {
            $referral_code = strtoupper(Str::random(10));
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'beneficiary',
            'status' => 'pending_profile',
            'referral_code' => $referral_code,
            'bank_name' => $this->bank_name ?: null,
            'account_number' => $this->account_number ?: null,
            'account_name' => $this->account_name ?: null,
            'swift_code' => $this->swift_code ?: null,
            'routing_number' => $this->routing_number ?: null,
            'email_verified_at' => now(), // Auto-verified since manual add by Admin
        ]);

        ActivityLog::log('manual_create', 'Manually created beneficiary account: ' . $user->name, auth()->id());
        $user->updateOnboardingStatus();

        $this->successMessage = 'Beneficiary added successfully!';
        $this->closeModal();
    }

    // Update Beneficiary details
    public function updateBeneficiary(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->selectedUserId,
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|numeric|digits_between:10,15',
            'account_name' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $oldStatus = $user->status;

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'bank_name' => $this->bank_name ?: null,
            'account_number' => $this->account_number ?: null,
            'account_name' => $this->account_name ?: null,
            'swift_code' => $this->swift_code ?: null,
            'routing_number' => $this->routing_number ?: null,
            'status' => $this->user_status,
        ]);

        if ($oldStatus !== $this->user_status) {
            ActivityLog::log('status_updated', 'Updated status to ' . strtoupper($this->user_status) . ' for beneficiary ' . $user->name, auth()->id());
        }

        ActivityLog::log('manual_edit', 'Manually updated profile fields for beneficiary ' . $user->name, auth()->id());

        $user->updateOnboardingStatus();

        $this->successMessage = 'Beneficiary details updated successfully!';
        $this->closeModal();
    }

    // Administrative Quick Action: Approve / Reject / Suspend / Reactivate
    public function updateStatus(int $userId, string $status): void
    {
        $user = User::findOrFail($userId);

        if ($status === 'active') {
            // Verify and prevent approval if contract not signed yet
            if (!$user->contract_accepted_at) {
                $this->errorMessage = 'Cannot approve this beneficiary. The official contract has not been read or accepted yet.';
                return;
            }
        }

        $user->update(['status' => $status]);
        ActivityLog::log('status_updated', 'Administratively changed status of ' . $user->name . ' to ' . strtoupper($status), auth()->id());

        $this->successMessage = 'Beneficiary status changed to ' . strtoupper($status) . ' successfully!';
    }

    // Document Review Actions
    public function selectDoc(int $docId): void
    {
        $this->selectedDocId = $docId;
        $doc = Document::findOrFail($docId);
        $this->admin_notes = $doc->admin_notes ?? '';
    }

    public function approveDoc(int $docId): void
    {
        $doc = Document::findOrFail($docId);
        $doc->update([
            'status' => 'approved',
            'admin_notes' => $this->admin_notes ?: 'Verified and approved.',
        ]);

        ActivityLog::log('document_approved', 'Approved document ' . Document::typeLabel($doc->document_type) . ' for ' . $doc->user->name, auth()->id());
        $doc->user->updateOnboardingStatus();

        $this->successMessage = 'Document approved!';
        $this->admin_notes = '';
        $this->selectedDocId = null;
    }

    public function rejectDoc(int $docId): void
    {
        $doc = Document::findOrFail($docId);
        $doc->update([
            'status' => 'rejected',
            'admin_notes' => $this->admin_notes ?: 'Document rejected. Please upload a clearer copy.',
        ]);

        ActivityLog::log('document_rejected', 'Rejected document ' . Document::typeLabel($doc->document_type) . ' for ' . $doc->user->name, auth()->id());
        $doc->user->updateOnboardingStatus();

        $this->successMessage = 'Document rejected.';
        $this->admin_notes = '';
        $this->selectedDocId = null;
    }

    // EXPORT BENEFICIARIES TO CSV
    public function exportCSV()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Beneficiaries_Report_" . now()->format('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $users = User::where('role', 'beneficiary')->orderBy('name')->get();

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Header Row
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Verification Status', 'Referral Code',
                'Referred By', 'Referrals Count', 'Bank Name', 'Account Number',
                'Account Name', 'Contract Accepted At', 'Status'
            ]);

            // Data Rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->email_verified_at ? 'Verified' : 'Unverified',
                    $user->referral_code,
                    $user->referrer ? $user->referrer->name : 'N/A',
                    $user->getVerifiedReferralsCount(),
                    $user->bank_name ?: 'N/A',
                    $user->account_number ?: 'N/A',
                    $user->account_name ?: 'N/A',
                    $user->contract_accepted_at ? $user->contract_accepted_at->format('Y-m-d H:i:s') : 'N/A',
                    strtoupper($user->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $query = User::where('role', 'beneficiary')->with(['referrer', 'documents']);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Referral filter
        if ($this->filterReferral) {
            $minRefs = (int)Setting::get('min_referrals_required', 3);
            if ($this->filterReferral === 'met') {
                $query->whereHas('referrals', function($q) {
                    $q->whereNotNull('email_verified_at');
                }, '>=', $minRefs);
            } elseif ($this->filterReferral === 'pending') {
                $query->whereHas('referrals', function($q) {
                    $q->whereNotNull('email_verified_at');
                }, '<', $minRefs);
            }
        }

        // Registration Date filter
        if ($this->filterDate) {
            if ($this->filterDate === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($this->filterDate === 'week') {
                $query->where('created_at', '>=', now()->subWeek());
            } elseif ($this->filterDate === 'month') {
                $query->where('created_at', '>=', now()->subMonth());
            }
        }

        $beneficiaries = $query->latest()->paginate(10);
        $selectedUser = $this->selectedUserId ? User::with('documents')->find($this->selectedUserId) : null;

        return view('livewire.admin-beneficiaries', [
            'beneficiaries' => $beneficiaries,
            'selectedUser' => $selectedUser,
            'minRefsRequired' => (int)Setting::get('min_referrals_required', 3),
        ])->layout('layouts.app');
    }
}
