<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Flash messages -->
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

        <!-- Banner Hero -->
        <div class="bg-gradient-to-r from-emerald-800 to-indigo-900 rounded-2xl shadow-xl overflow-hidden mb-8 text-white p-6 sm:p-10 relative">
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-12 -translate-y-12">
                <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"></path>
                </svg>
            </div>

            <div class="relative z-10 max-w-3xl">
                <span class="bg-emerald-50 text-slate-900 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider mb-3 inline-block">
                    Official Empowerment Program
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Welcome back, {{ $user->name }}!</h1>
                <p class="mt-2 text-lg text-emerald-100 font-light">
                    Your portal for tracking your monthly ₦20,000 payroll support.
                </p>

                <div class="mt-6 flex flex-wrap gap-3 items-center">
                    <span class="text-sm font-medium">Status:</span>
                    @if ($user->status === 'active')
                        <span class="bg-emerald-500 text-slate-950 font-semibold px-4 py-1.5 rounded-full text-xs shadow-md inline-flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-950 me-2 animate-ping"></span>
                            ACTIVE BENEFICIARY
                        </span>
                    @elseif ($user->status === 'pending_approval')
                        <span class="bg-amber-400 text-slate-950 font-semibold px-4 py-1.5 rounded-full text-xs shadow-md inline-flex items-center">
                            <span class="w-2 h-2 rounded-full bg-amber-950 me-2"></span>
                            AWAITING ADMIN APPROVAL
                        </span>
                    @elseif ($user->status === 'pending_contract')
                        <span class="bg-blue-500 text-white font-semibold px-4 py-1.5 rounded-full text-xs shadow-md inline-flex items-center">
                            AWAITING CONTRACT SIGNATURE
                        </span>
                    @elseif ($user->status === 'suspended')
                        <span class="bg-red-500 text-white font-semibold px-4 py-1.5 rounded-full text-xs shadow-md inline-flex items-center">
                            SUSPENDED
                        </span>
                    @elseif ($user->status === 'rejected')
                        <span class="bg-slate-500 text-white font-semibold px-4 py-1.5 rounded-full text-xs shadow-md inline-flex items-center">
                            REJECTED
                        </span>
                    @else
                        <span class="bg-slate-700 text-slate-300 font-semibold px-4 py-1.5 rounded-full text-xs shadow-md inline-flex items-center">
                            ONBOARDING (INCOMPLETE)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if ($user->status !== 'active')
            <!-- ONBOARDING FLOW AND CHECKLIST -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Onboarding Checklist -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-emerald-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Onboarding Checklist
                        </h2>
                        <p class="text-sm text-slate-600 mb-6">Complete all checklist requirements below. Once completed, download and accept the official program contract to queue your account for administrative approval.</p>

                        <div class="space-y-4">
                            @foreach ($checklist as $key => $item)
                                <div class="flex items-start p-4 rounded-xl border {{ $item['completed'] ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-white' }}">
                                    <div class="mt-0.5">
                                        @if ($item['completed'])
                                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500 text-white">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-600 text-xs font-bold">
                                                !
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ms-3 flex-1">
                                        <h3 class="font-bold text-slate-800 text-sm sm:text-base">{{ $item['label'] }}</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Profile & Bank details Form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-indigo-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Profile & Bank Account Details
                        </h2>

                        <form wire:submit="saveProfile" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Full Name</label>
                                    <input type="text" wire:model="name" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Your Full Name">
                                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Bank Name</label>
                                    <select wire:model="bank_name" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                        <option value="">Select a Bank</option>
                                        <option value="Access Bank">Access Bank</option>
                                        <option value="Guaranty Trust Bank (GTB)">Guaranty Trust Bank (GTB)</option>
                                        <option value="Zenith Bank">Zenith Bank</option>
                                        <option value="United Bank for Africa (UBA)">United Bank for Africa (UBA)</option>
                                        <option value="First Bank of Nigeria">First Bank of Nigeria</option>
                                        <option value="Union Bank">Union Bank</option>
                                        <option value="Fidelity Bank">Fidelity Bank</option>
                                        <option value="Sterling Bank">Sterling Bank</option>
                                        <option value="Wema Bank">Wema Bank</option>
                                        <option value="Stanbic IBTC Bank">Stanbic IBTC Bank</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('bank_name')" class="mt-1" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Account Number</label>
                                    <input type="text" wire:model="account_number" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="10-digit NUBAN account number">
                                    <x-input-error :messages="$errors->get('account_number')" class="mt-1" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Account Holder Name</label>
                                    <input type="text" wire:model="account_name" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Name as registered with Bank">
                                    <x-input-error :messages="$errors->get('account_name')" class="mt-1" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">SWIFT / BIC Code (Optional)</label>
                                    <input type="text" wire:model="swift_code" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="E.g. ACCBNGLa">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Routing Number (Optional)</label>
                                    <input type="text" wire:model="routing_number" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="International routing number">
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm shadow transition-all">
                                    Save details
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Upload Documents panel -->
                    @if (count($requiredDocs) > 0)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-emerald-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Verify Identity & Upload Documents
                            </h2>
                            <p class="text-sm text-slate-600 mb-6">Upload clear snapshots or scan copies. Maximum file size is 2MB (JPEG, JPG, PNG only).</p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                @foreach ($requiredDocs as $docType)
                                    <div class="border rounded-xl p-4 flex flex-col justify-between h-full bg-slate-50 border-slate-200">
                                        <div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Document Type</span>
                                                @if (isset($uploadedDocs[$docType]))
                                                    @if ($uploadedDocs[$docType] === 'approved')
                                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded font-bold uppercase">Approved</span>
                                                    @elseif ($uploadedDocs[$docType] === 'rejected')
                                                        <span class="bg-red-100 text-red-800 text-[10px] px-2 py-0.5 rounded font-bold uppercase">Rejected</span>
                                                    @else
                                                        <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded font-bold uppercase font-medium">Pending Check</span>
                                                    @endif
                                                @else
                                                    <span class="bg-slate-200 text-slate-600 text-[10px] px-2 py-0.5 rounded font-bold uppercase">Not Uploaded</span>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-slate-800 mt-2 text-sm">{{ \App\Models\Document::typeLabel($docType) }}</h3>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-slate-200/60">
                                            @if (isset($uploadedDocs[$docType]) && $uploadedDocs[$docType] === 'approved')
                                                <div class="flex items-center text-emerald-600 text-xs font-semibold">
                                                    <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Verification Completed
                                                </div>
                                            @else
                                                <div class="space-y-2">
                                                    <input type="file" wire:model="{{ $docType }}_file" class="hidden" id="file-input-{{ $docType }}">
                                                    <label for="file-input-{{ $docType }}" class="w-full flex items-center justify-center bg-white border border-slate-300 hover:border-emerald-500 rounded-lg text-xs font-medium py-1.5 px-3 text-slate-700 hover:text-emerald-600 cursor-pointer shadow-sm transition">
                                                        Select Image
                                                    </label>
                                                    @if ($this->{$docType . '_file'})
                                                        <div class="text-[10px] text-indigo-600 font-semibold truncate">{{ $this->{$docType . '_file'}->getClientOriginalName() }}</div>
                                                        <button type="button" wire:click="uploadDocument('{{ $docType }}')" class="w-full bg-emerald-600 text-white text-xs font-bold py-1.5 rounded-lg hover:bg-emerald-700 transition">
                                                            Upload Now
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Referrals & Contract accepting panel -->
                <div class="space-y-6">
                    <!-- Referral Box -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-indigo-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            My Referrals
                        </h2>

                        @if ($referralsEnabled)
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1 text-xs font-semibold text-slate-600">
                                    <span>Milestone progress</span>
                                    <span>{{ $verifiedRefs }} / {{ $minRefs }} verified referrals</span>
                                </div>
                                <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $referralProgress }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-slate-50 border rounded-xl p-4">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">My Unique Referral Link</label>
                            <div class="flex items-center space-x-2" x-data="{ copied: false, link: '{{ $user->getReferralLink() }}' }">
                                <input type="text" readonly :value="link" class="bg-white border-slate-200 rounded-lg text-xs w-full text-slate-600 focus:outline-none p-2 truncate">
                                <button type="button" @click="navigator.clipboard.writeText(link); copied = true; setTimeout(() => copied = false, 2000)" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-2 px-3 rounded-lg text-xs flex items-center transition shadow-sm shrink-0">
                                    <span x-show="!copied">Copy</span>
                                    <span x-show="copied" class="text-emerald-400">Copied!</span>
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-2">Prevent self-referrals and duplicate accounts. Only referrals who verify their email address count towards your target.</p>
                        </div>
                    </div>

                    <!-- Official Program Contract Sign Box -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-bold text-slate-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Official Program Contract
                        </h2>
                        <p class="text-xs text-slate-600 mb-4">You are required to download, thoroughly read, and officially agree to the program agreement contract terms.</p>

                        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-center mb-4">
                            <h3 class="text-sm font-bold text-indigo-900 mb-1">Contract File</h3>
                            <p class="text-xs text-indigo-700 mb-3">Download your personalized contract document containing your bank records.</p>

                            <a href="{{ route('contract.download') }}" class="w-full inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-xs shadow transition">
                                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Contract (.TXT)
                            </a>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" wire:model="agree_contract" wire:change="acceptContract" id="agree-contract-check" class="mt-1 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                            <label for="agree-contract-check" class="ms-2.5 text-xs text-slate-600 font-medium">
                                I have read and officially agree to all terms of the program contract.
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('agree_contract')" class="mt-2" />
                    </div>

                    <!-- Instructions and Status -->
                    <div class="bg-slate-800 rounded-2xl shadow-md border border-slate-700 text-white p-6">
                        <h2 class="text-base font-bold mb-2 flex items-center">
                            <svg class="w-5 h-5 text-emerald-400 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Validation Instructions
                        </h2>
                        <ul class="space-y-2 text-xs text-slate-300">
                            <li class="flex items-start">
                                <span class="text-emerald-400 me-1.5">✔</span>
                                Email must be verified (verified immediately upon registration).
                            </li>
                            <li class="flex items-start">
                                <span class="text-emerald-400 me-1.5">✔</span>
                                Bank details must be fully filled out and matches account holder name.
                            </li>
                            @if ($referralsEnabled)
                                <li class="flex items-start">
                                    <span class="text-emerald-400 me-1.5">✔</span>
                                    At least {{ $minRefs }} people must register using your link and verify their email.
                                </li>
                            @endif
                            <li class="flex items-start">
                                <span class="text-emerald-400 me-1.5">✔</span>
                                All required identity verification files must be uploaded.
                            </li>
                            <li class="flex items-start">
                                <span class="text-emerald-400 me-1.5">✔</span>
                                Contract terms must be downloaded, read, and accepted.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <!-- ACTIVE BENEFICIARY DASHBOARD -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Main Stats and Countdown Row -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Payments Disbursed</span>
                            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1">₦{{ number_format($totalDisbursed, 2) }}</div>
                            <span class="text-xs text-slate-500 mt-1 block">{{ $totalReceived }} payouts received</span>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Remaining Balance</span>
                            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1">₦{{ number_format($remainingAmount, 2) }}</div>
                            <span class="text-xs text-slate-500 mt-1 block">{{ $remainingPayments }} payouts left</span>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Current Month</span>
                                <div class="text-2xl sm:text-3xl font-extrabold text-indigo-600 mt-1">Month {{ min(12, $totalReceived + 1) }}/12</div>
                            </div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden mt-3">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ round(($totalReceived / 12) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 12-Month Roadmap Timeline (Vibrant Card UI) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-extrabold text-slate-900">12-Month Payroll Roadmap</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Visualize your full payroll journey across 12 months.</p>
                            </div>
                            <span class="text-xs bg-slate-100 text-slate-700 px-3 py-1 rounded-full font-bold">₦20,000 / month</span>
                        </div>

                        <!-- Roadmap timeline scrollable container -->
                        <div class="relative overflow-x-auto pb-4 pt-2">
                            <div class="flex min-w-[900px] justify-between items-start relative px-4">
                                <!-- Continuous Line -->
                                <div class="absolute left-6 right-6 top-8 h-1 bg-slate-200 z-0"></div>

                                @foreach ($roadmap as $idx => $step)
                                    <div class="flex flex-col items-center text-center w-20 relative z-10">
                                        <!-- Step circle depending on status -->
                                        @if ($step['status'] === 'Paid')
                                            <div class="w-12 h-12 rounded-full bg-emerald-500 border-4 border-white text-white flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif ($step['status'] === 'Pending')
                                            <div class="w-12 h-12 rounded-full bg-amber-400 border-4 border-white text-slate-900 flex items-center justify-center shadow-md animate-pulse">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @elseif ($step['status'] === 'Skipped')
                                            <div class="w-12 h-12 rounded-full bg-red-400 border-4 border-white text-white flex items-center justify-center shadow-md">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                        @elseif ($step['status'] === 'Upcoming')
                                            <div class="w-12 h-12 rounded-full bg-indigo-100 border-4 border-white text-indigo-600 flex items-center justify-center shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-slate-200 border-4 border-white text-slate-400 flex items-center justify-center shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <div class="font-bold text-xs text-slate-800">Month {{ $step['month'] }}</div>
                                            @if ($step['status'] === 'Paid')
                                                <div class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-1 py-0.5 rounded uppercase mt-1">Paid</div>
                                                <div class="text-[9px] text-slate-500 font-medium mt-1">{{ $step['date'] }}</div>
                                            @elseif ($step['status'] === 'Pending')
                                                <div class="text-[10px] bg-amber-100 text-amber-800 font-bold px-1 py-0.5 rounded uppercase mt-1">Pending</div>
                                            @elseif ($step['status'] === 'Skipped')
                                                <div class="text-[10px] bg-red-100 text-red-800 font-bold px-1 py-0.5 rounded uppercase mt-1">Skipped</div>
                                            @else
                                                <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Upcoming</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Recent Payroll Disbursed History Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-lg font-bold text-slate-900">Payroll Disbursement History</h2>
                            <p class="text-xs text-slate-500">A detailed statement of all monthly funds processed to your account.</p>
                        </div>
                        @if ($user->payments()->count() === 0)
                            <div class="p-8 text-center text-slate-500 text-sm">No payroll records processed yet.</div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b">
                                        <tr>
                                            <th class="py-3 px-6">Month</th>
                                            <th class="py-3 px-6">Payment Reference</th>
                                            <th class="py-3 px-6">Amount</th>
                                            <th class="py-3 px-6">Processing Date</th>
                                            <th class="py-3 px-6 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y text-slate-700">
                                        @foreach ($user->payments()->orderByDesc('month_number')->get() as $pmt)
                                            <tr class="hover:bg-slate-50/50">
                                                <td class="py-4 px-6 font-bold">Month {{ $pmt->month_number }}</td>
                                                <td class="py-4 px-6 font-mono text-xs">{{ $pmt->reference_number }}</td>
                                                <td class="py-4 px-6 font-bold text-slate-900">₦{{ number_format($pmt->amount, 2) }}</td>
                                                <td class="py-4 px-6 text-slate-500">{{ $pmt->payment_date ? $pmt->payment_date->format('d M Y') : 'Pending' }}</td>
                                                <td class="py-4 px-6 text-right">
                                                    @if ($pmt->status === 'Paid')
                                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">PAID</span>
                                                    @elseif ($pmt->status === 'Pending')
                                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase animate-pulse">PENDING</span>
                                                    @else
                                                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">SKIPPED</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN: TIMER & REFERRAL & ACTIVITY -->
                <div class="space-y-8">
                    <!-- Dynamic Live countdown timer (Alpine.js) -->
                    <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
                        <div class="absolute right-0 bottom-0 opacity-10">
                            <svg class="w-36 h-36" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                            </svg>
                        </div>

                        <h3 class="text-sm font-bold uppercase tracking-widest text-emerald-400 mb-4">NEXT PAYROLL RUN DATE</h3>

                        <!-- Alpine Ticking Countdown -->
                        <div x-data="{
                            target: {{ $targetTimestamp }},
                            now: Math.floor(Date.now() / 1000),
                            days: 0,
                            hours: 0,
                            minutes: 0,
                            seconds: 0,
                            updateTimer() {
                                let diff = this.target - this.now;
                                if (diff <= 0) {
                                    this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                                    return;
                                }
                                this.days = Math.floor(diff / (60 * 60 * 24));
                                diff -= this.days * 60 * 60 * 24;
                                this.hours = Math.floor(diff / (60 * 60));
                                diff -= this.hours * 60 * 60;
                                this.minutes = Math.floor(diff / 60);
                                this.seconds = diff % 60;
                            },
                            init() {
                                this.updateTimer();
                                setInterval(() => {
                                    this.now = Math.floor(Date.now() / 1000);
                                    this.updateTimer();
                                }, 1000);
                            }
                        }" class="flex justify-between items-center bg-slate-900/40 p-4 rounded-xl border border-white/5 shadow-inner">
                            <div class="flex flex-col items-center">
                                <span class="text-2xl sm:text-3xl font-black" x-text="days">00</span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-wider mt-1">Days</span>
                            </div>
                            <div class="text-xl font-bold text-slate-400">:</div>
                            <div class="flex flex-col items-center">
                                <span class="text-2xl sm:text-3xl font-black" x-text="hours">00</span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-wider mt-1">Hours</span>
                            </div>
                            <div class="text-xl font-bold text-slate-400">:</div>
                            <div class="flex flex-col items-center">
                                <span class="text-2xl sm:text-3xl font-black" x-text="minutes">00</span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-wider mt-1">Mins</span>
                            </div>
                            <div class="text-xl font-bold text-slate-400">:</div>
                            <div class="flex flex-col items-center">
                                <span class="text-2xl sm:text-3xl font-black text-emerald-400" x-text="seconds">00</span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-wider mt-1">Secs</span>
                            </div>
                        </div>

                        <p class="text-[11px] text-indigo-200 mt-4 leading-relaxed">
                            Payroll processing is officially executed on the <strong class="text-emerald-400">{{ $processingDay }}th day</strong> of each month.
                        </p>
                    </div>

                    <!-- Designated Bank Details Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-base font-extrabold text-slate-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-indigo-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Designated Bank Details
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Bank Name</span>
                                <span class="font-bold text-slate-800">{{ $user->bank_name }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Account Number</span>
                                <span class="font-bold text-slate-800 font-mono">{{ $user->account_number }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-slate-500">Account Name</span>
                                <span class="font-bold text-slate-800">{{ $user->account_name }}</span>
                            </div>
                            @if ($user->swift_code)
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-slate-500">SWIFT / BIC Code</span>
                                    <span class="font-bold text-slate-800 font-mono">{{ $user->swift_code }}</span>
                                </div>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-500 mt-3 italic">To edit your bank credentials, please update your record inside profile settings page.</p>
                    </div>

                    <!-- Referral Status & unique link -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-base font-extrabold text-slate-900 mb-3">Unique Program Growth Link</h3>
                        <p class="text-xs text-slate-600 mb-4">Every beneficiary gets a referral link to promote program outreach.</p>

                        <div class="bg-slate-50 border rounded-xl p-3" x-data="{ copied: false, link: '{{ $user->getReferralLink() }}' }">
                            <div class="flex items-center space-x-2">
                                <input type="text" readonly :value="link" class="bg-white border-slate-200 rounded-lg text-xs w-full text-slate-600 focus:outline-none p-2 truncate">
                                <button type="button" @click="navigator.clipboard.writeText(link); copied = true; setTimeout(() => copied = false, 2000)" class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-1.5 px-3 rounded-lg text-xs flex items-center transition shadow-sm shrink-0">
                                    <span x-show="!copied">Copy</span>
                                    <span x-show="copied" class="text-emerald-400">Copied</span>
                                </button>
                            </div>
                            <div class="mt-3 flex justify-between items-center text-xs text-slate-500">
                                <span>Verified Referrals</span>
                                <span class="font-bold text-slate-800">{{ $verifiedRefs }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Feed (Logs) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-base font-extrabold text-slate-900 mb-4">Recent Account Activity</h3>
                        <div class="space-y-4">
                            @foreach ($activityLogs as $log)
                                <div class="flex items-start">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 me-2 shrink-0"></div>
                                    <div class="flex-1">
                                        <div class="text-xs font-bold text-slate-800 uppercase tracking-wide">{{ ucwords(str_replace('_', ' ', $log->action)) }}</div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $log->details }}</p>
                                        <span class="text-[9px] text-slate-400 font-semibold block mt-0.5">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
