<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Two-Factor Authentication') }}
        </h2>
        @if (auth()->user()->two_factor_secret)
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Two-factor authentication is enabled.') }}
            </p>
        @else
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Two-factor authentication is not enabled.') }}
            </p>
        @endif

    </header>
    @if (auth()->user()->two_factor_secret)
        <p>Scan the following QR code using your authenticator app.</p>
        {!! auth()->user()->twoFactorQrCodeSvg() !!}

        <p>Save these recovery codes in a safe place:</p>
        <ul>
            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes, true)) as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>
        <form method="POST" action="{{ url('user/two-factor-authentication') }}">
            @csrf
            <x-primary-button>Disable</x-primary-button>
        </form>
    @else
        <form method="POST" action="{{ url('user/two-factor-authentication') }}">
            @csrf
            <x-primary-button>Enable</x-primary-button>
        </form>
    @endif
</section>
