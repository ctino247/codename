<div class="py-10 bg-slate-50 min-h-screen" x-data="{ bulkOpen: @entangle('confirmBulkOpen'), editOpen: @entangle('editMode') }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Alerts -->
        @if ($successMessage)
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-lg shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-500 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $successMessage }}</span>
                </div>
                <button wire:click="$set('successMessage', null)" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
            </div>
        @endif

        @if ($errorMessage)
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $errorMessage }}</span>
                </div>
                <button wire:click="$set('errorMessage', null)" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
            </div>
        @endif

        <!-- Payroll Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Payroll Management</h1>
                <p class="text-sm text-slate-500 mt-1">Disburse bulk monthly grants, edit transaction ledgers, and download financial spreadsheets.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <button type="button" wire:click="exportPayrollCSV" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold py-2.5 px-4 rounded-lg text-sm shadow-sm transition flex items-center">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    Export Payroll Ledger
                </button>
                <button type="button" @click="bulkOpen = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm shadow transition flex items-center">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Process Bulk Monthly Payroll
                </button>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Beneficiaries</span>
                <div class="text-3xl font-black text-slate-800 mt-1">{{ $activeCount }}</div>
                <span class="text-xs text-slate-500 mt-1 block">Receiving ₦20,000 monthly</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Projected Bulk Payroll Amount</span>
                <div class="text-3xl font-black text-indigo-600 mt-1">₦{{ number_format($monthlyDisbAmount, 2) }}</div>
                <span class="text-xs text-slate-500 mt-1 block">Active beneficiaries combined</span>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Disbursed Funds</span>
                <div class="text-3xl font-black text-emerald-600 mt-1">₦{{ number_format($totalDisbursed, 2) }}</div>
                <span class="text-xs text-slate-500 mt-1 block">All-time Paid ledger</span>
            </div>
        </div>

        <!-- Filter Component Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Search Beneficiary</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Beneficiary name or email...">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Payment Status</label>
                    <select wire:model.live="filterStatus" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Skipped">Skipped</option>
                    </select>
                </div>

                <!-- Month Number -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Program Month</label>
                    <select wire:model.live="filterMonth" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Months</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">Month {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Clear -->
                <div>
                    <button type="button" wire:click="$set('search', ''); $set('filterStatus', ''); $set('filterMonth', '');" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-lg text-xs transition border">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Payroll Ledger Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-4 px-6">Beneficiary Details</th>
                            <th class="py-4 px-6">Bank Account</th>
                            <th class="py-4 px-6">Disbursement Month</th>
                            <th class="py-4 px-6">Reference ID</th>
                            <th class="py-4 px-6">Amount</th>
                            <th class="py-4 px-6">Processed Date</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-slate-700 divide-slate-100">
                        @forelse ($payments as $pmt)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $pmt->user ? $pmt->user->name : 'Deleted Beneficiary' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $pmt->user ? $pmt->user->email : '' }}</div>
                                </td>
                                <td class="py-4 px-6 text-xs">
                                    @if ($pmt->user && $pmt->user->bank_name)
                                        <div class="font-bold text-slate-800">{{ $pmt->user->bank_name }}</div>
                                        <div class="font-mono mt-0.5">{{ $pmt->user->account_number }}</div>
                                    @else
                                        <span class="text-slate-400 italic">Not set</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-slate-100 text-slate-800 text-xs font-bold px-2 py-1 rounded">
                                        Month {{ $pmt->month_number }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-mono text-xs text-indigo-600">
                                    {{ $pmt->reference_number }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    ₦{{ number_format($pmt->amount, 2) }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500">
                                    {{ $pmt->payment_date ? $pmt->payment_date->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="py-4 px-6">
                                    @if ($pmt->status === 'Paid')
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">PAID</span>
                                    @elseif ($pmt->status === 'Pending')
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">PENDING</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">SKIPPED</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button type="button" wire:click="openEditModal({{ $pmt->id }})" class="text-indigo-600 hover:text-indigo-955 hover:bg-indigo-50 border border-slate-200 px-2 py-1 rounded text-xs font-bold transition">
                                        Edit Record
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-500 text-sm">No payroll entries processed matching criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        </div>

        <!-- MODAL: BULK DISBURSEMENT WARNING -->
        <div x-show="bulkOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border text-center">
                <div class="mx-auto w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-2">Confirm Bulk Payroll Process</h3>
                <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                    This action will automatically process the monthly grant disbursement of <strong class="text-slate-900">₦20,000</strong> for all <strong class="text-emerald-600">{{ $activeCount }} currently active beneficiaries</strong> who have not yet hit the maximum duration. This action cannot be undone.
                </p>

                <div class="flex space-x-2">
                    <button type="button" @click="bulkOpen = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-lg text-sm border">
                        Cancel Action
                    </button>
                    <button type="button" wire:click="processBulkPayroll" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg text-sm shadow">
                        Yes, Execute Bulk Payout
                    </button>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT SINGLE TRANSACTION -->
        <div x-show="editOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-lg font-bold text-slate-900">Edit Single Payroll Entry</h3>
                    <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                @if ($selectedPayment)
                    <div class="pt-4 space-y-4">
                        <div class="bg-slate-50 p-3 rounded-lg text-xs space-y-1.5 border">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Beneficiary</span>
                                <span class="font-bold text-slate-800">{{ $selectedPayment->user ? $selectedPayment->user->name : 'Deleted User' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Program Month</span>
                                <span class="font-bold text-slate-800">Month {{ $selectedPayment->month_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Reference Number</span>
                                <span class="font-bold text-slate-800 font-mono">{{ $selectedPayment->reference_number }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Disbursement Status</label>
                            <select wire:model="pay_status" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Skipped">Skipped</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Admin Transaction Notes</label>
                            <textarea wire:model="admin_notes" rows="3" class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="State reasons or transaction details here..."></textarea>
                        </div>

                        <div class="pt-4 flex justify-end space-x-2 border-t mt-6">
                            <button type="button" @click="editOpen = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 px-4 rounded-lg text-xs font-bold border">Cancel</button>
                            <button type="button" wire:click="updatePayment" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-6 rounded-lg text-xs font-bold shadow">Save Payment</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
