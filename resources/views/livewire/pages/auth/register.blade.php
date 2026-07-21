<?php

use App\Models\User;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?string $ref = '';
    public ?string $referrerName = null;
    public bool $isRegistrationOpen = true;

    public function mount(?string $ref = null): void
    {
        $this->isRegistrationOpen = Setting::get('registration_open', '1') === '1';

        $this->ref = $ref ?: request()->query('ref');
        if ($this->ref) {
            $referrer = User::where('referral_code', $this->ref)->first();
            if ($referrer) {
                $this->referrerName = $referrer->name;
            }
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        if (!Setting::get('registration_open', '1') === '1') {
            $this->addError('email', 'Registration is currently closed.');
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find referrer if code is set
        $referrerId = null;
        if ($this->ref) {
            $referrer = User::where('referral_code', $this->ref)->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        // Generate unique referral code
        $referral_code = strtoupper(Str::random(10));
        while (User::where('referral_code', $referral_code)->exists()) {
            $referral_code = strtoupper(Str::random(10));
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'beneficiary',
            'status' => 'pending_profile',
            'referral_code' => $referral_code,
            'referred_by' => $referrerId,
        ]);

        ActivityLog::log('register', 'Registered a new beneficiary account' . ($referrerId ? ' referred by ' . $this->referrerName : ''), $user->id);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Join the Payroll Program</h2>
        <p class="text-sm text-slate-600 mt-1">Get ₦20,000 monthly for 12 months upon meeting requirements.</p>
    </div>

    @if(!$isRegistrationOpen)
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm text-center">
            <strong>Registration is currently closed.</strong> We are not accepting new beneficiary applications at this time. Please check back later.
        </div>
        <div class="mt-6 text-center">
            <a class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md" href="{{ route('login') }}" wire:navigate>
                {{ __('Go to Login') }}
            </a>
        </div>
    @else
        @if ($referrerName)
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm flex items-center">
                <svg class="w-5 h-5 me-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                <span>You are registering via <strong>{{ $referrerName }}'s</strong> referral link.</span>
            </div>
        @endif

        <form wire:submit="register">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" placeholder="Enter your full name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" placeholder="name@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}" wire:navigate>
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    @endif
</div>
