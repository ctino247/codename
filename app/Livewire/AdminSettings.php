<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminSettings extends Component
{
    use WithFileUploads;

    // Setting Form fields
    public string $monthly_payroll_amount = '';
    public string $total_program_duration = '';
    public string $min_referrals_required = '';
    public bool $referrals_required_enabled = true;
    public bool $registration_open = true;
    public string $payroll_processing_date = '';
    public string $program_start_date = '';
    public string $program_end_date = '';
    public string $site_footer = '';
    public string $sms_provider = '';

    // File upload
    public $contract_pdf;

    // Alerts
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized administrative access.');
        }

        $this->monthly_payroll_amount = Setting::get('monthly_payroll_amount', '20000');
        $this->total_program_duration = Setting::get('total_program_duration', '12');
        $this->min_referrals_required = Setting::get('min_referrals_required', '3');
        $this->referrals_required_enabled = Setting::get('referrals_required_enabled', '1') === '1';
        $this->registration_open = Setting::get('registration_open', '1') === '1';
        $this->payroll_processing_date = Setting::get('payroll_processing_date', '25');
        $this->program_start_date = Setting::get('program_start_date', '2026-01-01');
        $this->program_end_date = Setting::get('program_end_date', '2026-12-31');
        $this->site_footer = Setting::get('site_footer', 'Payroll Beneficiary Management Platform © 2026');
        $this->sms_provider = Setting::get('sms_provider', 'twilio');
    }

    public function saveSettings(): void
    {
        $this->validate([
            'monthly_payroll_amount' => 'required|numeric|min:1',
            'total_program_duration' => 'required|integer|min:1|max:36',
            'min_referrals_required' => 'required|integer|min:0|max:50',
            'payroll_processing_date' => 'required|integer|min:1|max:31',
            'program_start_date' => 'required|date',
            'program_end_date' => 'required|date|after_or_equal:program_start_date',
            'site_footer' => 'required|string|max:255',
            'sms_provider' => 'required|string|max:50',
        ]);

        Setting::set('monthly_payroll_amount', $this->monthly_payroll_amount);
        Setting::set('total_program_duration', $this->total_program_duration);
        Setting::set('min_referrals_required', $this->min_referrals_required);
        Setting::set('referrals_required_enabled', $this->referrals_required_enabled ? '1' : '0');
        Setting::set('registration_open', $this->registration_open ? '1' : '0');
        Setting::set('payroll_processing_date', $this->payroll_processing_date);
        Setting::set('program_start_date', $this->program_start_date);
        Setting::set('program_end_date', $this->program_end_date);
        Setting::set('site_footer', $this->site_footer);
        Setting::set('sms_provider', $this->sms_provider);

        // Upload custom contract if provided
        if ($this->contract_pdf) {
            $this->validate([
                'contract_pdf' => 'required|mimes:pdf,txt|max:5120', // 5MB Max (PDF or TXT)
            ]);

            $path = $this->contract_pdf->store('contracts', 'public');
            Setting::set('contract_pdf_path', $path);

            ActivityLog::log('settings_updated', 'Uploaded a brand new official program contract document', auth()->id());
            $this->contract_pdf = null; // reset
        }

        ActivityLog::log('settings_updated', 'Updated system configuration parameters', auth()->id());

        $this->successMessage = 'System configuration parameters updated successfully!';
    }

    public function render()
    {
        $currentContractPath = Setting::get('contract_pdf_path');

        return view('livewire.admin-settings', [
            'currentContractPath' => $currentContractPath,
        ])->layout('layouts.app');
    }
}
