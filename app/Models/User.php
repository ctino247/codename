<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

#[Fillable([
    'name', 'email', 'password',
    'role', 'status', 'referral_code', 'referred_by',
    'profile_completed_at', 'contract_accepted_at',
    'bank_name', 'account_number', 'account_name', 'swift_code', 'routing_number'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'profile_completed_at' => 'datetime',
            'contract_accepted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role checks
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isBeneficiary(): bool
    {
        return $this->role === 'beneficiary';
    }

    // Relationships
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    // Get verified referrals count
    public function getVerifiedReferralsCount(): int
    {
        return $this->referrals()->whereNotNull('email_verified_at')->count();
    }

    // Unique referral link
    public function getReferralLink(): string
    {
        return route('register', ['ref' => $this->referral_code]);
    }

    /**
     * Update the user onboarding status automatically.
     */
    public function updateOnboardingStatus(): void
    {
        if (in_array($this->status, ['active', 'suspended', 'rejected'])) {
            return; // Don't override final administrative statuses
        }

        // 1. Check Profile & Bank details
        $hasProfile = !empty($this->bank_name) && !empty($this->account_number) && !empty($this->account_name);

        if (!$hasProfile) {
            $this->status = 'pending_profile';
            $this->save();
            return;
        }

        // 2. Check Documents
        $requiredDocsSetting = Setting::get('required_documents', '[]');
        $requiredDocs = json_decode($requiredDocsSetting, true) ?: [];
        if (count($requiredDocs) > 0) {
            $uploadedDocsCount = $this->documents()->whereIn('document_type', $requiredDocs)->where('status', '!=', 'rejected')->count();
            if ($uploadedDocsCount < count($requiredDocs)) {
                $this->status = 'pending_profile';
                $this->save();
                return;
            }
        }

        // 3. Check Referrals
        $referralsEnabled = Setting::get('referrals_required_enabled', '1') === '1';
        if ($referralsEnabled) {
            $minRefs = (int)Setting::get('min_referrals_required', 3);
            if ($this->getVerifiedReferralsCount() < $minRefs) {
                $this->status = 'pending_profile';
                $this->save();
                return;
            }
        }

        // 4. Check Contract
        if (!$this->contract_accepted_at) {
            $this->status = 'pending_contract';
            $this->save();
            return;
        }

        // 5. Ready for Admin Approval!
        $this->status = 'pending_approval';
        $this->save();
    }
}
