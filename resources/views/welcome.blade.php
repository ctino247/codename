<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Payroll Empowerment Portal</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-slate-50 text-slate-800">

        <!-- Navbar -->
        <nav class="bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-2">
                        <span class="bg-emerald-600 p-2 rounded-lg text-white font-extrabold text-lg shadow-md">
                            ₦
                        </span>
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">
                            Payroll<span class="text-emerald-600">Empower</span>
                        </span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="/faq" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition">
                            FAQs / Information
                        </a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-5 rounded-lg shadow-sm transition">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-bold bg-slate-900 hover:bg-slate-800 text-white py-2 px-4 rounded-lg transition shadow-sm">
                                        Apply Now
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="bg-gradient-to-b from-white to-slate-100 py-16 sm:py-24 border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider mb-4 inline-block">
                    NGO & Corporate CSR Initiative
                </span>
                <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-900 tracking-tight leading-none">
                    Financial Empowerment <br class="hidden sm:inline" />
                    <span class="text-emerald-600">Made Transparent</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto font-light leading-relaxed">
                    A production-ready payroll management platform designed to provide verified program beneficiaries with a monthly grant of <strong class="text-slate-900">₦20,000 every month for 12 months</strong>.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-8 rounded-xl text-base shadow-lg hover:shadow-emerald-100 transition">
                        Register as Beneficiary
                    </a>
                    <a href="{{ route('login') }}" class="bg-white hover:bg-slate-50 text-slate-800 font-bold py-3.5 px-8 rounded-xl text-base border shadow-sm transition">
                        Admin Login Panel
                    </a>
                </div>
            </div>
        </header>

        <!-- Dynamic Statistics / Program Parameters Section -->
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="p-6">
                        <div class="text-emerald-600 text-3xl font-black mb-2">₦20,000</div>
                        <h3 class="font-bold text-slate-800 text-base">Monthly Support</h3>
                        <p class="text-xs text-slate-500 mt-1">Direct bank transfers to certified beneficiaries.</p>
                    </div>
                    <div class="p-6 border-y md:border-y-0 md:border-x">
                        <div class="text-indigo-600 text-3xl font-black mb-2">12 Months</div>
                        <h3 class="font-bold text-slate-800 text-base">Full Program Cycle</h3>
                        <p class="text-xs text-slate-500 mt-1">100% transparent tracking from Month 1 to Month 12.</p>
                    </div>
                    <div class="p-6">
                        <div class="text-emerald-600 text-3xl font-black mb-2">₦240,000</div>
                        <h3 class="font-bold text-slate-800 text-base">Total Disbursement</h3>
                        <p class="text-xs text-slate-500 mt-1">Disbursed per beneficiary upon full cycle completion.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Program Features -->
        <section class="py-16 sm:py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Structured Onboarding Flow</h2>
                    <p class="text-sm text-slate-600 mt-2">Our platform uses a robust multi-step verification pipeline to guarantee compliance, validation, and zero double-accounts.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center font-bold text-sm mb-4">1</div>
                        <h3 class="font-extrabold text-slate-800 mb-2">Complete Profile</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Beneficiaries verify their email and provide a valid bank account under their registered legal name.</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm mb-4">2</div>
                        <h3 class="font-extrabold text-slate-800 mb-2">Audited Verification</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Required identification documents are uploaded and carefully evaluated by administrators.</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center font-bold text-sm mb-4">3</div>
                        <h3 class="font-extrabold text-slate-800 mb-2">Community Growth</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Beneficiaries participate in growing the outreach by referring verified, non-duplicate applicants.</p>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white p-6 rounded-2xl border shadow-sm">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm mb-4">4</div>
                        <h3 class="font-extrabold text-slate-800 mb-2">Official Contract</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">Approved candidates download, read, and sign the official program agreement contract.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white py-12 border-t text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="font-medium text-slate-600">Payroll Beneficiary Management Platform</p>
                <p class="mt-2 text-slate-400">© 2026 NGO & CSR Initiative. This system is designed solely for public empowerment grants tracking.</p>
            </div>
        </footer>

    </body>
</html>
