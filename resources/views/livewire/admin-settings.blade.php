<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

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

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Settings</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Fine-tune disbursement schedules, referral validation logic, branding, and contract documents.</p>
        </div>

        <form wire:submit="saveSettings" class="space-y-6">

            <!-- SECTION 1: PAYROLL CONFIGURATION -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg me-2.5">
                        ₦
                    </span>
                    Payroll & Disbursement Settings
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Monthly Payroll Grant (₦)</label>
                        <input type="number" wire:model="monthly_payroll_amount" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold" required>
                        <p class="text-[10px] text-slate-400 mt-1">Default monthly grant given to each active beneficiary (e.g. 20000).</p>
                        <x-input-error :messages="$errors->get('monthly_payroll_amount')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Program Duration (Months)</label>
                        <input type="number" wire:model="total_program_duration" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold" required>
                        <p class="text-[10px] text-slate-400 mt-1">Maximum months a beneficiary can receive disbursements (e.g. 12).</p>
                        <x-input-error :messages="$errors->get('total_program_duration')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Payroll Processing Day</label>
                        <input type="number" wire:model="payroll_processing_date" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold" min="1" max="31" required>
                        <p class="text-[10px] text-slate-400 mt-1">Day of the month when payroll runs (1-31).</p>
                        <x-input-error :messages="$errors->get('payroll_processing_date')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Program Start Date</label>
                        <input type="date" wire:model="program_start_date" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold" required>
                        <x-input-error :messages="$errors->get('program_start_date')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Program End Date</label>
                        <input type="date" wire:model="program_end_date" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold" required>
                        <x-input-error :messages="$errors->get('program_end_date')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ONBOARDING & REFERRAL PARAMETERS -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg me-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </span>
                    Onboarding & Referral Rules
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <!-- Toggle Registration -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="reg-open" wire:model="registration_open" class="focus:ring-emerald-500 h-4.5 w-4.5 text-emerald-600 border-slate-300 rounded">
                        </div>
                        <div class="ms-3 text-sm">
                            <label for="reg-open" class="font-bold text-slate-800">Registration Open</label>
                            <p class="text-slate-500 text-xs mt-0.5">Toggle whether new users can register on the platform. If disabled, a "closed" alert shows.</p>
                        </div>
                    </div>

                    <!-- Toggle Referrals -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="ref-req-enabled" wire:model="referrals_required_enabled" class="focus:ring-indigo-500 h-4.5 w-4.5 text-indigo-600 border-slate-300 rounded">
                        </div>
                        <div class="ms-3 text-sm">
                            <label for="ref-req-enabled" class="font-bold text-slate-800">Enable Referral Requirement</label>
                            <p class="text-slate-500 text-xs mt-0.5">Toggle whether beneficiaries are required to refer a set milestone count of people before getting approved.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t pt-6" x-show="$wire.referrals_required_enabled">
                    <div class="max-w-md">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Minimum Referrals Required</label>
                        <input type="number" wire:model="min_referrals_required" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold">
                        <p class="text-[10px] text-slate-400 mt-1">Number of verified email signups a beneficiary must refer to satisfy requirement (default 3).</p>
                        <x-input-error :messages="$errors->get('min_referrals_required')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- SECTION 3: OFFICIAL PROGRAM CONTRACT PDF -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                    <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg me-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </span>
                    Official Program Contract PDF/TXT
                </h2>
                <p class="text-xs text-slate-500 mb-6">Replace the official program agreement. Beneficiaries must download and check the confirmation box before getting approved.</p>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Current Contract Document</span>
                        @if ($currentContractPath)
                            <span class="text-xs font-bold text-slate-700 font-mono mt-1 block truncate max-w-[400px]">{{ $currentContractPath }}</span>
                        @else
                            <span class="text-xs font-bold text-emerald-600 mt-1 block">Dynamic Default Program Contract (TXT) active</span>
                        @endif
                    </div>
                    <div class="mt-3 sm:mt-0">
                        <a href="{{ route('contract.download') }}" class="inline-flex bg-slate-800 text-white font-bold py-1.5 px-4 rounded text-xs">
                            Verify Active Download
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Upload New Contract File (.PDF or .TXT)</label>
                    <input type="file" wire:model="contract_pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1">Maximum file size 5MB.</p>
                    <x-input-error :messages="$errors->get('contract_pdf')" class="mt-1" />
                </div>
            </div>

            <!-- SECTION 4: SITE BRANDING & SMS -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg me-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </span>
                    Site Branding & SMS Integration (Future-Ready)
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Site Footer / Copywrite Text</label>
                        <input type="text" wire:model="site_footer" class="w-full rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold" required>
                        <x-input-error :messages="$errors->get('site_footer')" class="mt-1" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">SMS Integration Gateway Provider</label>
                        <select wire:model="sms_provider" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="twilio">Twilio SMS API Gateway</option>
                            <option value="infobip">Infobip Global SMS</option>
                            <option value="termii">Termii SMS Gateway (Nigeria)</option>
                            <option value="africastalking">AfricasTalking SMS API</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">SMS capabilities are structured as future-ready configurations.</p>
                        <x-input-error :messages="$errors->get('sms_provider')" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Form submission button -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 px-8 rounded-xl text-sm shadow-md hover:shadow-lg transition">
                    Save Configuration Changes
                </button>
            </div>

        </form>

    </div>
</div>
