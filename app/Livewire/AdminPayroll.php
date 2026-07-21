<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminPayroll extends Component
{
    use WithPagination;

    // Filters and search
    public string $search = '';
    public string $filterStatus = ''; // '', 'Paid', 'Pending', 'Skipped'
    public string $filterMonth = ''; // '', 1 to 12

    // Bulk action state
    public bool $confirmBulkOpen = false;

    // Single payroll record edit state
    public bool $editMode = false;
    public ?int $selectedPaymentId = null;
    public string $pay_status = 'Pending';
    public string $admin_notes = '';

    // Alerts
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterMonth' => ['except' => ''],
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

    // PROCESS BULK MONTHLY PAYMENTS
    public function processBulkPayroll(): void
    {
        $activeUsers = User::where('role', 'beneficiary')->where('status', 'active')->get();
        $monthlyAmount = (float)Setting::get('monthly_payroll_amount', 20000);
        $duration = (int)Setting::get('total_program_duration', 12);

        $disbursedCount = 0;
        $totalProcessedAmount = 0;

        foreach ($activeUsers as $user) {
            // Find current paid count
            $paidCount = $user->payments()->where('status', 'Paid')->count();

            // Check if they have reached the maximum program limit
            if ($paidCount >= $duration) {
                // Already completed program! We don't disburse anymore
                continue;
            }

            // Next month is $paidCount + 1
            $nextMonth = $paidCount + 1;

            // Make sure they don't have a 'Paid' payment for this month number already
            $alreadyPaid = $user->payments()->where('month_number', $nextMonth)->where('status', 'Paid')->exists();
            if ($alreadyPaid) {
                continue;
            }

            // Create reference number
            $refNumber = 'PAY-M' . $nextMonth . '-' . strtoupper(Str::random(12));
            while (Payment::where('reference_number', $refNumber)->exists()) {
                $refNumber = 'PAY-M' . $nextMonth . '-' . strtoupper(Str::random(12));
            }

            // Create or update a Pending payment to Paid, or create new Paid record
            Payment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'month_number' => $nextMonth,
                ],
                [
                    'amount' => $monthlyAmount,
                    'payment_date' => now(),
                    'status' => 'Paid',
                    'reference_number' => $refNumber,
                    'admin_notes' => 'Bulk processed on ' . now()->format('Y-m-d H:i:s'),
                ]
            );

            ActivityLog::log('payment_processed', 'Disbursed Month ' . $nextMonth . ' payroll (₦' . number_format($monthlyAmount, 2) . ')', $user->id);
            $disbursedCount++;
            $totalProcessedAmount += $monthlyAmount;
        }

        if ($disbursedCount > 0) {
            ActivityLog::log('bulk_payroll', 'Processed bulk monthly payroll for ' . $disbursedCount . ' active beneficiaries. Total disbursed: ₦' . number_format($totalProcessedAmount, 2), auth()->id());
            $this->successMessage = 'Bulk payroll disbursement completed! Successfully processed ' . $disbursedCount . ' active beneficiaries.';
        } else {
            $this->errorMessage = 'No eligible active beneficiaries found requiring disbursements for their next program month.';
        }

        $this->confirmBulkOpen = false;
    }

    // Individual Edit
    public function openEditModal(int $paymentId): void
    {
        $this->selectedPaymentId = $paymentId;
        $pmt = Payment::findOrFail($paymentId);
        $this->pay_status = $pmt->status;
        $this->admin_notes = $pmt->admin_notes ?? '';
        $this->editMode = true;
    }

    public function closeEditModal(): void
    {
        $this->selectedPaymentId = null;
        $this->admin_notes = '';
        $this->editMode = false;
    }

    public function updatePayment(): void
    {
        $pmt = Payment::findOrFail($this->selectedPaymentId);
        $oldStatus = $pmt->status;

        $pmt->update([
            'status' => $this->pay_status,
            'admin_notes' => $this->admin_notes ?: null,
            'payment_date' => $this->pay_status === 'Paid' ? ($pmt->payment_date ?? now()) : $pmt->payment_date,
        ]);

        if ($oldStatus !== $this->pay_status) {
            ActivityLog::log('payment_status_updated', 'Updated payroll status of Month ' . $pmt->month_number . ' to ' . strtoupper($this->pay_status) . ' for ' . $pmt->user->name, auth()->id());
        }

        $this->successMessage = 'Payroll record updated successfully!';
        $this->closeEditModal();
    }

    // EXPORT PAYROLL REPORT TO CSV
    public function exportPayrollCSV()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Payroll_Disbursements_Ledger_" . now()->format('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $payments = Payment::with('user')->orderByDesc('created_at')->get();

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');

            // Header Row
            fputcsv($file, [
                'ID', 'Beneficiary Name', 'Beneficiary Email', 'Bank Name',
                'Account Number', 'Month Number', 'Amount (₦)', 'Disbursement Date',
                'Payment Reference', 'Status', 'Admin Notes'
            ]);

            // Data Rows
            foreach ($payments as $pmt) {
                fputcsv($file, [
                    $pmt->id,
                    $pmt->user ? $pmt->user->name : 'N/A',
                    $pmt->user ? $pmt->user->email : 'N/A',
                    $pmt->user ? ($pmt->user->bank_name ?: 'N/A') : 'N/A',
                    $pmt->user ? ($pmt->user->account_number ?: 'N/A') : 'N/A',
                    'Month ' . $pmt->month_number,
                    $pmt->amount,
                    $pmt->payment_date ? $pmt->payment_date->format('Y-m-d') : 'N/A',
                    $pmt->reference_number,
                    strtoupper($pmt->status),
                    $pmt->admin_notes ?: 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $activeCount = User::where('role', 'beneficiary')->where('status', 'active')->count();
        $monthlyDisbAmount = $activeCount * (float)Setting::get('monthly_payroll_amount', 20000);
        $totalDisbursed = Payment::where('status', 'Paid')->sum('amount');

        $query = Payment::with('user');

        if ($this->search) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterMonth) {
            $query->where('month_number', $this->filterMonth);
        }

        $payments = $query->orderByDesc('created_at')->paginate(10);
        $selectedPayment = $this->selectedPaymentId ? Payment::with('user')->find($this->selectedPaymentId) : null;

        return view('livewire.admin-payroll', [
            'payments' => $payments,
            'activeCount' => $activeCount,
            'monthlyDisbAmount' => $monthlyDisbAmount,
            'totalDisbursed' => $totalDisbursed,
            'selectedPayment' => $selectedPayment,
        ])->layout('layouts.app');
    }
}
