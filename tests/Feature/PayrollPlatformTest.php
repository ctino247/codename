<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class PayrollPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic settings
        Setting::set('monthly_payroll_amount', '20000');
        Setting::set('total_program_duration', '12');
        Setting::set('min_referrals_required', '3');
        Setting::set('referrals_required_enabled', '1');
        Setting::set('registration_open', '1');
        Setting::set('payroll_processing_date', '25');
    }

    /**
     * Test home page returns a successful response.
     */
    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Payroll');
        $response->assertSee('Empowerment');
    }

    /**
     * Test FAQ page returns a successful response.
     */
    public function test_faqpage_returns_successful_response(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
        $response->assertSee('Information Hub');
        $response->assertSee('₦20,000');
    }

    /**
     * Test beneficiary registration via Livewire Volt component.
     */
    public function test_beneficiary_registration_generates_unique_referral_code(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('name', 'Alice Parker')
            ->set('email', 'alice@payroll.org')
            ->set('password', 'SecurePassword123!')
            ->set('password_confirmation', 'SecurePassword123!')
            ->call('register');

        $component->assertRedirect('/dashboard');

        $user = User::where('email', 'alice@payroll.org')->first();
        $this->assertNotNull($user);
        $this->assertEquals('beneficiary', $user->role);
        $this->assertEquals('pending_profile', $user->status);
        $this->assertNotNull($user->referral_code);
        $this->assertNull($user->referred_by);
    }

    /**
     * Test registration with a referral code.
     */
    public function test_registration_with_referral_code_attributes_referrer(): void
    {
        // Create a referrer
        $referrer = User::create([
            'name' => 'John Referrer',
            'email' => 'referrer@payroll.org',
            'password' => 'password',
            'role' => 'beneficiary',
            'status' => 'pending_profile',
            'referral_code' => 'SUPERCODE7',
        ]);

        // Access register page with ref parameter
        $response = $this->get('/register?ref=SUPERCODE7');
        $response->assertStatus(200);
        $response->assertSee('John Referrer');

        // Test registering with the ref code via Volt
        $component = Volt::test('pages.auth.register', ['ref' => 'SUPERCODE7'])
            ->set('name', 'Bob Referred')
            ->set('email', 'referred@payroll.org')
            ->set('password', 'SecurePassword123!')
            ->set('password_confirmation', 'SecurePassword123!')
            ->call('register');

        $component->assertRedirect('/dashboard');

        $referredUser = User::where('email', 'referred@payroll.org')->first();
        $this->assertNotNull($referredUser);
        $this->assertEquals($referrer->id, $referredUser->referred_by);
    }

    /**
     * Test contract download serves personalized file.
     */
    public function test_contract_download_serves_personalized_file(): void
    {
        $user = User::create([
            'name' => 'Jane Beneficiary',
            'email' => 'jane@payroll.org',
            'password' => 'password',
            'role' => 'beneficiary',
            'status' => 'pending_profile',
            'bank_name' => 'Zenith Bank',
            'account_number' => '1020304050',
            'account_name' => 'Jane Beneficiary',
        ]);

        $this->actingAs($user);

        $response = $this->get('/contract/download');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Jane Beneficiary');
        $response->assertSee('Zenith Bank');
        $response->assertSee('1020304050');
        $response->assertSee('₦20,000.00');
    }

    /**
     * Test admin routes are secured and restricted.
     */
    public function test_admin_routes_are_secured_and_restricted(): void
    {
        // 1. Guest access gets redirected
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        // 2. Beneficiary gets 403 Forbidden
        $beneficiary = User::create([
            'name' => 'Just Beneficiary',
            'email' => 'justb@payroll.org',
            'password' => 'password',
            'role' => 'beneficiary',
        ]);
        $beneficiary->email_verified_at = now();
        $beneficiary->save();

        $this->actingAs($beneficiary);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);

        // 3. Admin gets 200 OK
        $admin = User::create([
            'name' => 'Program Admin',
            'email' => 'admin@payroll.org',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $admin->email_verified_at = now();
        $admin->save();

        $this->actingAs($admin);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test user cannot be approved without accepting contract.
     */
    public function test_user_cannot_be_approved_without_contract_acceptance(): void
    {
        $user = User::create([
            'name' => 'Sonia Green',
            'email' => 'sonia@payroll.org',
            'password' => 'password',
            'role' => 'beneficiary',
            'status' => 'pending_profile',
            'bank_name' => 'Access Bank',
            'account_number' => '0123456789',
            'account_name' => 'Sonia Green',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'superadmin@payroll.org',
            'password' => 'password',
            'role' => 'super_admin',
        ]);
        $admin->email_verified_at = now();
        $admin->save();

        $this->actingAs($admin);

        // Call the Livewire AdminBeneficiaries component status approval action directly
        $component = \Livewire\Livewire::test(\App\Livewire\AdminBeneficiaries::class);
        $component->call('updateStatus', $user->id, 'active');

        // Sonia's status should NOT change to active because contract is not accepted
        $user->refresh();
        $this->assertNotEquals('active', $user->status);
    }
}
