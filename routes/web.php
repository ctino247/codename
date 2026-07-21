<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsAdmin;

// Landing Homepage
Route::view('/', 'welcome');

// Public FAQ / Information Page
Route::view('faq', 'faq')->name('faq');

// Unified Dashboard Entry Point
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Administrative Secured Routes
Route::middleware(['auth', 'verified', EnsureUserIsAdmin::class])->group(function () {
    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('admin/beneficiaries', 'admin.beneficiaries')->name('admin.beneficiaries');
    Route::view('admin/payroll', 'admin.payroll')->name('admin.payroll');
    Route::view('admin/settings', 'admin.settings')->name('admin.settings');
});

// Download Contract Route (Generates personalized contract download safely)
Route::get('contract/download', function() {
    $path = \App\Models\Setting::get('contract_pdf_path');
    if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        return \Illuminate\Support\Facades\Storage::disk('public')->download($path);
    }

    // Fallback: Generates a highly detailed, professional program contract document dynamically
    $user = auth()->user();
    $userName = $user ? $user->name : 'Beneficiary';
    $userEmail = $user ? $user->email : 'Email';
    $bankName = $user ? ($user->bank_name ?? 'Not Provided') : 'Not Provided';
    $accNum = $user ? ($user->account_number ?? 'Not Provided') : 'Not Provided';
    $amount = number_format((float)\App\Models\Setting::get('monthly_payroll_amount', 20000), 2);
    $duration = \App\Models\Setting::get('total_program_duration', 12);
    $acceptDate = $user && $user->contract_accepted_at ? $user->contract_accepted_at->format('Y-m-d H:i:s') : 'Not Signed Yet';

    $content = <<<TEXT
========================================================================
             OFFICIAL PAYROLL BENEFICIARY PROGRAM CONTRACT
========================================================================

This agreement is entered into between the Payroll Program Administrator and
the Approved Beneficiary, whose details are recorded below:

Beneficiary Name:  {$userName}
Beneficiary Email: {$userEmail}
Bank Designated:   {$bankName}
Account Number:    {$accNum}

TERMS & CONDITIONS:
1. Under this empowerment initiative, the Beneficiary is eligible to receive
   a monthly grant disbursement of ₦{$amount} for a maximum duration of
   {$duration} months, subject to meeting all initial and ongoing program criteria.
2. The Beneficiary certifies that all profile information and bank account
   details provided are accurate and belong solely to the Beneficiary.
3. This grant is not an investment, loan, or MLM system. No fees or deposits
   are required to participate or withdraw.
4. The Beneficiary agrees to use the disbursed funds responsibly for personal
   development, business support, or welfare purposes.
5. The Administrator reserves the right to suspend or terminate disbursements
   should any information be found fraudulent or if program regulations are breached.

------------------------------------------------------------------------
Accepted Date & Time: {$acceptDate}
========================================================================
TEXT;

    return response($content)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', 'attachment; filename="Payroll_Program_Contract_' . ($user ? $user->id : 'Guest') . '.txt"');
})->middleware(['auth'])->name('contract.download');

require __DIR__.'/auth.php';
