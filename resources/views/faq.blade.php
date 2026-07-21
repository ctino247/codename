<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Program Information & FAQs</title>
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
                        <a href="/" class="flex items-center space-x-2">
                            <span class="bg-emerald-600 p-2 rounded-lg text-white font-extrabold text-lg shadow-md">
                                ₦
                            </span>
                            <span class="font-extrabold text-xl tracking-tight text-slate-900">
                                Payroll<span class="text-emerald-600">Empower</span>
                            </span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="/" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition">
                            Home
                        </a>
                        <a href="/faq" class="text-sm font-bold text-emerald-600 transition">
                            FAQs
                        </a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-5 rounded-lg shadow-sm transition">
                                    My Dashboard
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

        <!-- Header Hero banner -->
        <div class="bg-slate-900 text-white py-12 sm:py-16">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Information Hub & FAQs</h1>
                <p class="text-slate-400 mt-2 text-sm sm:text-base leading-relaxed">
                    Have questions about the application requirements, referral goals, contract acceptance, or payment dates? Read our official guidelines.
                </p>
            </div>
        </div>

        <!-- FAQ Content Accordion-like cards -->
        <div class="max-w-4xl mx-auto px-4 py-12 sm:py-16 space-y-8">

            <!-- Quick stats banner -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between shadow-sm">
                <div>
                    <h3 class="font-bold text-emerald-950 text-base sm:text-lg">Need support getting started?</h3>
                    <p class="text-xs sm:text-sm text-emerald-800 mt-1 max-w-xl">Applications are fully open for eligible candidates. Complete your details and get approved to join the next disbursement batch.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="/register" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition shadow">
                        Apply Online
                    </a>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="space-y-4">
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-6">Frequently Asked Questions</h2>

                <!-- Question 1 -->
                <div class="bg-white rounded-xl shadow-sm border p-5 sm:p-6">
                    <h3 class="font-extrabold text-base text-slate-900">What is the Payroll Empowerment Program?</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        It is a formal social investment grant program designed to support business startups, digital freelancers, or welfare beneficiaries. Once approved, beneficiaries receive a monthly disbursement of <strong>₦20,000 every month for 12 consecutive months</strong>.
                    </p>
                </div>

                <!-- Question 2 -->
                <div class="bg-white rounded-xl shadow-sm border p-5 sm:p-6">
                    <h3 class="font-extrabold text-base text-slate-900">Is this a loan, HYIP, MLM, or trading scheme?</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        <strong>No.</strong> This is 100% NOT an investment, loan, Multi-Level Marketing (MLM), or trading system. Beneficiaries are never asked to pay any deposit, fee, or buy package products. It is entirely funded by public empowerment schemes, corporate CSR, or foundation grants.
                    </p>
                </div>

                <!-- Question 3 -->
                <div class="bg-white rounded-xl shadow-sm border p-5 sm:p-6">
                    <h3 class="font-extrabold text-base text-slate-900">How do I qualify and get approved?</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        To guarantee transparency, every candidate must:
                    </p>
                    <ul class="list-disc list-inside text-xs sm:text-sm text-slate-600 mt-2 space-y-1.5 pl-2">
                        <li>Register and verify their email address.</li>
                        <li>Provide accurate profile bank account records belonging to their legal name.</li>
                        <li>Upload clean snapshots of required credentials (e.g. National ID, Passport snapshot).</li>
                        <li>Support communal program growth by sharing their unique link and referring a minimum of 3 verified people.</li>
                        <li>Download, read, and accept the official program contract agreement.</li>
                    </ul>
                </div>

                <!-- Question 4 -->
                <div class="bg-white rounded-xl shadow-sm border p-5 sm:p-6">
                    <h3 class="font-extrabold text-base text-slate-900">When is the monthly payroll processed?</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Administrative bulk payrolls are officially finalized and processed on the <strong>25th day of every month</strong>. Beneficiaries can check their real-time live dashboard for the countdown timer.
                    </p>
                </div>

                <!-- Question 5 -->
                <div class="bg-white rounded-xl shadow-sm border p-5 sm:p-6">
                    <h3 class="font-extrabold text-base text-slate-900">Can I self-refer or register duplicate bank accounts?</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        <strong>No.</strong> The system has strict validation filters. Duplicate bank account numbers or emails are immediately flagged, and administrators reserve the right to administratively suspend or reject fraudulent applicants. Only verified referrals count towards your milestone progress.
                    </p>
                </div>
            </div>

            <!-- Support section -->
            <div class="bg-slate-100 rounded-2xl border p-6 text-center">
                <h3 class="font-bold text-slate-900 text-base">Have more questions?</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">Our administrative team is happy to guide you through verification requirements.</p>
                <a href="mailto:support@payroll.org" class="text-xs font-bold text-indigo-600 hover:underline">support@payroll.org</a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white py-12 border-t text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="font-medium text-slate-600">Payroll Beneficiary Management Platform</p>
                <p class="mt-2 text-slate-400">© 2026 NGO & CSR Initiative. This system is designed solely for public empowerment grants tracking.</p>
            </div>
        </footer>

    </body>
</html>
