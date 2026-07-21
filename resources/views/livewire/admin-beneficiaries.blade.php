<div class="py-10 bg-slate-50 min-h-screen" x-data="{ addOpen: @entangle('manuallyAddMode'), editOpen: @entangle('editMode'), docsOpen: @entangle('viewDocsMode') }">
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

        <!-- Directory Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Beneficiaries Directory</h1>
                <p class="text-sm text-slate-500 mt-1">Search, audit documents, edit credentials, and approve eligible program candidates.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <button type="button" wire:click="exportCSV" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold py-2.5 px-4 rounded-lg text-sm shadow-sm transition flex items-center">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    Export CSV
                </button>
                <button type="button" wire:click="openAddModal" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg text-sm shadow transition flex items-center">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Beneficiary
                </button>
            </div>
        </div>

        <!-- Filter Component Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Search Directory</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Search name or email address...">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Onboarding Status</label>
                    <select wire:model.live="filterStatus" class="w-full text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="pending_profile">Pending Profile / Docs</option>
                        <option value="pending_contract">Pending Contract</option>
                        <option value="pending_approval">Pending Approval</option>
                        <option value="active">Active Beneficiaries</option>
                        <option value="suspended">Suspended Accounts</option>
                        <option value="rejected">Rejected Accounts</option>
                    </select>
                </div>

                <!-- Referrals -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Referral Milestone</label>
                    <select wire:model.live="filterReferral" class="w-full text-sm text-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Milestone Progress</option>
                        <option value="met">Milestone Completed ({{ $minRefsRequired }}+)</option>
                        <option value="pending">Milestone Incomplete</option>
                    </select>
                </div>

                <!-- Action buttons -->
                <div>
                    <button type="button" wire:click="clearFilters" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-lg text-xs transition border">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Directory Listing Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-4 px-6">Beneficiary Details</th>
                            <th class="py-4 px-6">Referrals</th>
                            <th class="py-4 px-6">Bank Info</th>
                            <th class="py-4 px-6">Accepted Contract?</th>
                            <th class="py-4 px-6">Status Badge</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-slate-700 divide-slate-100">
                        @forelse ($beneficiaries as $user)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium mt-1">Joined: {{ $user->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-800 text-sm">
                                        {{ $user->getVerifiedReferralsCount() }} verified
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Code: <span class="font-mono text-indigo-600 font-bold">{{ $user->referral_code }}</span></div>
                                    @if ($user->referrer)
                                        <div class="text-[9px] text-slate-400 font-semibold mt-1 uppercase">Referred by: {{ $user->referrer->name }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-xs">
                                    @if ($user->bank_name)
                                        <div class="font-bold text-slate-800">{{ $user->bank_name }}</div>
                                        <div class="font-mono mt-0.5">{{ $user->account_number }}</div>
                                        <div class="text-slate-400 mt-0.5 truncate max-w-[150px]">{{ $user->account_name }}</div>
                                    @else
                                        <span class="text-slate-400 italic">Not completed</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if ($user->contract_accepted_at)
                                        <div class="text-emerald-600 text-xs font-bold flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span>
                                            Accepted
                                        </div>
                                        <div class="text-[9px] text-slate-400 mt-1">{{ $user->contract_accepted_at->format('d M Y, H:i') }}</div>
                                    @else
                                        <span class="text-red-500 text-xs font-semibold flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 me-1.5"></span>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if ($user->status === 'active')
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">ACTIVE</span>
                                    @elseif ($user->status === 'pending_approval')
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">PENDING AUDIT</span>
                                    @elseif ($user->status === 'pending_contract')
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">PENDING SIGN</span>
                                    @elseif ($user->status === 'suspended')
                                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">SUSPENDED</span>
                                    @elseif ($user->status === 'rejected')
                                        <span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">REJECTED</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">INCOMPLETE</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap space-x-1">
                                    <button type="button" wire:click="openDocsModal({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 border border-slate-200 px-2 py-1 rounded text-xs font-bold transition">
                                        Audit Docs
                                    </button>
                                    <button type="button" wire:click="openEditModal({{ $user->id }})" class="text-slate-700 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 px-2 py-1 rounded text-xs font-bold transition">
                                        Edit
                                    </button>
                                    @if ($user->status === 'pending_approval')
                                        <button type="button" wire:click="updateStatus({{ $user->id }}, 'active')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-1 rounded text-xs font-bold transition shadow-sm">
                                            Approve
                                        </button>
                                    @elseif ($user->status === 'active')
                                        <button type="button" wire:click="updateStatus({{ $user->id }}, 'suspended')" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-2 py-1 rounded text-xs font-bold transition">
                                            Suspend
                                        </button>
                                    @elseif ($user->status === 'suspended')
                                        <button type="button" wire:click="updateStatus({{ $user->id }}, 'active')" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-2 py-1 rounded text-xs font-bold transition">
                                            Reactivate
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 text-sm">No beneficiaries match your filter constraints.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100">
                {{ $beneficiaries->links() }}
            </div>
        </div>

        <!-- MODAL: MANUAL ADD -->
        <div x-show="addOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-xl font-bold text-slate-900">Manually Add Beneficiary</h3>
                    <button @click="addOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <form wire:submit="storeBeneficiary" class="space-y-4 pt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Full Name</label>
                            <input type="text" wire:model="name" class="w-full text-sm rounded-lg border-slate-200" required>
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Email Address</label>
                            <input type="email" wire:model="email" class="w-full text-sm rounded-lg border-slate-200" required>
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Temporary Password</label>
                        <input type="password" wire:model="password" class="w-full text-sm rounded-lg border-slate-200" required>
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Bank Details (Optional)</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Bank Name</label>
                                <input type="text" wire:model="bank_name" class="w-full text-sm rounded-lg border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Number</label>
                                <input type="text" wire:model="account_number" class="w-full text-sm rounded-lg border-slate-200">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Account Name</label>
                            <input type="text" wire:model="account_name" class="w-full text-sm rounded-lg border-slate-200">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end space-x-2 border-t mt-6">
                        <button type="button" @click="addOpen = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 px-4 rounded-lg text-sm font-bold border">Cancel</button>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-6 rounded-lg text-sm font-bold shadow">Save Beneficiary</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: EDIT BENEFICIARY -->
        <div x-show="editOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-xl font-bold text-slate-900">Edit Beneficiary details</h3>
                    <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <form wire:submit="updateBeneficiary" class="space-y-4 pt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Full Name</label>
                            <input type="text" wire:model="name" class="w-full text-sm rounded-lg border-slate-200" required>
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Email Address</label>
                            <input type="email" wire:model="email" class="w-full text-sm rounded-lg border-slate-200" required>
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Administrative Status</label>
                            <select wire:model="user_status" class="w-full text-sm rounded-lg border-slate-200">
                                <option value="pending_profile">Pending Profile / Documents</option>
                                <option value="pending_contract">Pending Contract</option>
                                <option value="pending_approval">Pending Approval</option>
                                <option value="active">Active Beneficiary</option>
                                <option value="suspended">Suspended</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <x-input-error :messages="$errors->get('user_status')" />
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Bank Details</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Bank Name</label>
                                <input type="text" wire:model="bank_name" class="w-full text-sm rounded-lg border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Account Number</label>
                                <input type="text" wire:model="account_number" class="w-full text-sm rounded-lg border-slate-200">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Account Name</label>
                            <input type="text" wire:model="account_name" class="w-full text-sm rounded-lg border-slate-200">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end space-x-2 border-t mt-6">
                        <button type="button" @click="editOpen = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 px-4 rounded-lg text-sm font-bold border">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-6 rounded-lg text-sm font-bold shadow">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: AUDIT DOCUMENTS -->
        <div x-show="docsOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
            <div class="bg-white rounded-2xl max-w-4xl w-full p-6 shadow-2xl border max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-xl font-bold text-slate-900">Audit Documents for {{ $selectedUser ? $selectedUser->name : '' }}</h3>
                    <button @click="docsOpen = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                @if ($selectedUser && $selectedUser->documents()->count() === 0)
                    <div class="text-center p-8 text-slate-500 text-sm">No verification documents uploaded yet.</div>
                @elseif ($selectedUser)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <!-- Doc List -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Uploaded Verification Files</h4>

                            @foreach ($selectedUser->documents as $doc)
                                <div wire:click="selectDoc({{ $doc->id }})" class="p-4 border rounded-xl cursor-pointer transition hover:bg-slate-50 {{ $selectedDocId === $doc->id ? 'border-indigo-500 bg-indigo-50/20 shadow-sm' : 'border-slate-200 bg-white' }}">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-semibold text-indigo-600">{{ \App\Models\Document::typeLabel($doc->document_type) }}</span>
                                        @if ($doc->status === 'approved')
                                            <span class="bg-emerald-100 text-emerald-800 text-[9px] px-2 py-0.5 rounded font-bold uppercase">Approved</span>
                                        @elseif ($doc->status === 'rejected')
                                            <span class="bg-red-100 text-red-800 text-[9px] px-2 py-0.5 rounded font-bold uppercase">Rejected</span>
                                        @else
                                            <span class="bg-amber-100 text-amber-800 text-[9px] px-2 py-0.5 rounded font-bold uppercase animate-pulse">Pending Review</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-1">Uploaded: {{ $doc->created_at->format('d M Y, H:i') }}</div>
                                    @if ($doc->admin_notes)
                                        <div class="text-[10px] text-slate-500 italic mt-2 bg-slate-50 p-2 rounded border border-slate-100">Notes: {{ $doc->admin_notes }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Doc Review / Preview Panel -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            @if ($selectedDocId)
                                @php $activeDoc = \App\Models\Document::find($selectedDocId); @endphp
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Audit Review Panel</h4>

                                <div class="mb-4">
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Document Snapshot File</span>
                                    <div class="mt-2 border rounded-lg overflow-hidden bg-black max-h-[220px] flex items-center justify-center shadow-inner">
                                        <!-- Serving uploaded file safely -->
                                        <img src="{{ Storage::url($activeDoc->file_path) }}" class="object-contain max-h-[220px]" alt="Document snap">
                                    </div>
                                    <a href="{{ Storage::url($activeDoc->file_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline mt-2 inline-block font-semibold">Open image in new window &rarr;</a>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Administrative Evaluation Notes</label>
                                        <textarea wire:model="admin_notes" rows="3" class="w-full text-xs rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="State reason for approval or clear correction guidelines for rejection..."></textarea>
                                    </div>

                                    <div class="flex space-x-1">
                                        <button type="button" wire:click="approveDoc({{ $activeDoc->id }})" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded text-xs shadow">Approve Document</button>
                                        <button type="button" wire:click="rejectDoc({{ $activeDoc->id }})" class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold py-2 rounded text-xs">Reject Document</button>
                                    </div>
                                </div>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center text-slate-400 p-8">
                                    <svg class="w-12 h-12 mb-2 stroke-current" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <p class="text-xs font-semibold">Select a verification document file on the left side to review and preview here.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="pt-6 flex justify-end border-t mt-6">
                    <button type="button" @click="docsOpen = false" class="bg-slate-800 hover:bg-slate-900 text-white py-2 px-6 rounded-lg text-sm font-bold shadow">Done Auditing</button>
                </div>
            </div>
        </div>

    </div>
</div>
