<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('You can use a recovery code instead if you do not have access to your authentication app.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('2fa.verify.recovery') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="recovery_code" :value="__('Two-Factor Authentication Recovery Code')" />
            <x-text-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" :value="old('recovery_code')"
                required autofocus />
            <x-input-error :messages="$errors->get('recovery_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
