<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\ActivityLog;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized administrative access.');
        }
    }

    public function render()
    {
        // 1. Core Counts
        $totalBeneficiaries = User::where('role', 'beneficiary')->count();
        $activeBeneficiaries = User::where('role', 'beneficiary')->where('status', 'active')->count();
        $pendingApprovals = User::where('role', 'beneficiary')->where('status', 'pending_approval')->count();
        $suspendedCount = User::where('role', 'beneficiary')->where('status', 'suspended')->count();

        // 2. Financial Metrics
        $monthlyAmount = (float)Setting::get('monthly_payroll_amount', 20000);
        $projectedMonthlyPayroll = $activeBeneficiaries * $monthlyAmount;
        $totalDisbursed = Payment::where('status', 'Paid')->sum('amount');

        // 3. Referral Statistics
        $referralRequirementEnabled = Setting::get('referrals_required_enabled', '1') === '1';
        $minRefsRequired = (int)Setting::get('min_referrals_required', 3);
        $totalReferralsCount = User::whereNotNull('referred_by')->count();

        $beneficiariesWithReferrals = User::where('role', 'beneficiary')->has('referrals')->count();
        $averageReferrals = $totalBeneficiaries > 0 ? round($totalReferralsCount / $totalBeneficiaries, 1) : 0;

        // 4. Program Completion Statistics
        $programDuration = (int)Setting::get('total_program_duration', 12);
        // Find beneficiaries who have received $programDuration paid payments
        $completedBeneficiariesCount = User::where('role', 'beneficiary')
            ->whereHas('payments', function ($query) {
                $query->where('status', 'Paid');
            }, '>=', $programDuration)
            ->count();

        // 5. Recent System Activities
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('livewire.admin-dashboard', [
            'totalBeneficiaries' => $totalBeneficiaries,
            'activeBeneficiaries' => $activeBeneficiaries,
            'pendingApprovals' => $pendingApprovals,
            'suspendedCount' => $suspendedCount,
            'projectedMonthlyPayroll' => $projectedMonthlyPayroll,
            'totalDisbursed' => $totalDisbursed,
            'averageReferrals' => $averageReferrals,
            'totalReferralsCount' => $totalReferralsCount,
            'completedBeneficiariesCount' => $completedBeneficiariesCount,
            'recentActivities' => $recentActivities,
            'minRefsRequired' => $minRefsRequired,
            'referralRequirementEnabled' => $referralRequirementEnabled,
            'programDuration' => $programDuration,
        ])->layout('layouts.app');
    }
}
