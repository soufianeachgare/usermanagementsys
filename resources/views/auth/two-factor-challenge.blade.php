<x-guest-layout>
    <div class="container">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Two-Factor Authentication</h1>
    </div>
    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div class="mb-3">
            <label for="code">Authentication Code</label>
            <input type="text" id="2fa_code" name="2fa_code" class="form-control" required autofocus>
            <small class="form-text text-muted">
                Enter the code from your authentication app.
            </small>
        </div>
        <div class="flex items-center justify-end mt-4">

            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('two-factor.recovery.login') }}">
                {{ __('use a recovery code?') }}
            </a>
            <x-primary-button>Verify</x-primary-button>
        </div>
        @if ($errors->any())
            <div class="mt-3 alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
    </div>
</x-guest-layout>
