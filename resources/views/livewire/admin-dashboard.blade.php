<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Administrative Overview</h1>
                <p class="text-sm text-slate-500 mt-1">Real-time statistics, completion trackers, and system audit trails.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <a href="{{ route('admin.beneficiaries') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm shadow transition">
                    Manage Beneficiaries
                </a>
                <a href="{{ route('admin.payroll') }}" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 px-5 rounded-lg text-sm shadow transition">
                    Disburse Payroll
                </a>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Metric 1 -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Registered</span>
                    <div class="text-3xl font-black text-slate-800 mt-1">{{ $totalBeneficiaries }}</div>
                    <span class="text-xs text-slate-500 mt-1 block">Beneficiary accounts</span>
                </div>
                <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Metric 2 -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Beneficiaries</span>
                    <div class="text-3xl font-black text-emerald-600 mt-1">{{ $activeBeneficiaries }}</div>
                    <span class="text-xs text-slate-500 mt-1 block">Currently receiving support</span>
                </div>
                <div class="bg-emerald-50 p-3 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Metric 3 -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Approvals</span>
                    <div class="text-3xl font-black text-amber-500 mt-1">{{ $pendingApprovals }}</div>
                    <span class="text-xs text-slate-500 mt-1 block">Awaiting document audit</span>
                </div>
                <div class="bg-amber-50 p-3 rounded-xl text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <!-- Metric 4 -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suspended</span>
                    <div class="text-3xl font-black text-red-500 mt-1">{{ $suspendedCount }}</div>
                    <span class="text-xs text-slate-500 mt-1 block">Temporarily locked accounts</span>
                </div>
                <div class="bg-red-50 p-3 rounded-xl text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Secondary Metrics Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Financial reports -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Financial Records</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-xs text-slate-500 font-bold uppercase block">Total Funds Disbursed</span>
                        <div class="text-3xl font-extrabold text-indigo-600 mt-1">₦{{ number_format($totalDisbursed, 2) }}</div>
                        <p class="text-[10px] text-slate-400 mt-1">All-time payment payouts</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-xs text-slate-500 font-bold uppercase block">Next Projected Monthly Payroll</span>
                        <div class="text-2xl font-extrabold text-slate-800 mt-1">₦{{ number_format($projectedMonthlyPayroll, 2) }}</div>
                        <p class="text-[10px] text-slate-400 mt-1">Based on {{ $activeBeneficiaries }} active beneficiaries</p>
                    </div>
                </div>
            </div>

            <!-- Program Outreach & Referrals -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Referrals & Growth Outreach</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-slate-500">Total Program Referrals</span>
                        <span class="font-bold text-slate-800">{{ $totalReferralsCount }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-slate-500">Average Referrals / Beneficiary</span>
                        <span class="font-bold text-slate-800">{{ $averageReferrals }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-slate-500">Min Referral Requirement</span>
                        <span class="font-bold text-slate-800">
                            @if ($referralRequirementEnabled)
                                {{ $minRefsRequired }} verified accounts
                            @else
                                <span class="text-slate-400 line-through">Disabled</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-indigo-50 text-indigo-800 text-xs rounded-xl font-medium leading-relaxed">
                    Referral criteria is purely intended to secure the program's communal outreach. This is NOT a financial commission or pyramid system.
                </div>
            </div>

            <!-- Completion statistics -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Program Completion Rate</h3>
                    <p class="text-xs text-slate-500">Track beneficiaries who have successfully received the complete {{ $programDuration }}-month support cycle.</p>
                </div>

                <div class="my-6 text-center">
                    <div class="inline-block relative">
                        <div class="text-4xl font-black text-slate-800">{{ $completedBeneficiariesCount }}</div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mt-1">Graduated</div>
                    </div>
                </div>

                <div class="bg-emerald-50 text-emerald-800 rounded-xl p-3 text-xs leading-relaxed font-medium">
                    Once a beneficiary reaches {{ $programDuration }} received payments, their monthly disbursements automatically freeze, marking their status as "Graduated".
                </div>
            </div>
        </div>

        <!-- Audit Activity Trail -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-900">System Activity Audit Log</h3>
                <p class="text-xs text-slate-500">A comprehensive chronological ledger of administrative reviews, payment processes, and onboarding updates.</p>
            </div>

            <div class="p-6 max-h-[400px] overflow-y-auto">
                <div class="relative border-l-2 border-slate-150 pl-6 space-y-6">
                    @forelse ($recentActivities as $log)
                        <div class="relative">
                            <!-- Bullet -->
                            <span class="absolute -left-[31px] top-1 bg-slate-100 text-slate-700 w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center text-[10px] font-bold">
                                •
                            </span>
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                                    <h4 class="font-bold text-sm text-slate-800 uppercase tracking-wide">
                                        {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                    </h4>
                                    <span class="text-xs text-slate-400 font-semibold">{{ $log->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $log->details }}</p>
                                <div class="flex items-center space-x-2 mt-1 text-[10px] text-slate-400 font-semibold uppercase">
                                    <span>User: {{ $log->user ? $log->user->name : 'System/Guest' }}</span>
                                    <span>•</span>
                                    <span>IP: {{ $log->ip_address }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-slate-500 py-6">No audit activities recorded yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
