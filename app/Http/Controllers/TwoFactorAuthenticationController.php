<?php

// app/Http/Controllers/TwoFactorAuthenticationController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        // Generate a new secret
        $google2fa = new Google2FA();
        $user->two_factor_secret = encrypt($google2fa->generateSecretKey());

        // Generate recovery codes
        $user->two_factor_recovery_codes = encrypt(json_encode(collect(range(1, 8))->map(function () {
            return Str::random(10) . '-' . Str::random(10);
        })->all()));

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Two-factor authentication enabled.');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Two-factor authentication disabled.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|numeric',
        ]);

        $user = User::find(Auth::id());
        $google2fa = new Google2FA();

        // Decrypt the user's two_factor_secret
        $secret = decrypt($user->two_factor_secret);

        // Verify the provided code
        $valid = $google2fa->verifyKey($secret, $request->input('2fa_code'));

        if ($valid) {
            // Mark the user as having passed the 2FA challenge
            $request->session()->put('two_factor_authenticated', true);

            // Redirect the user to their intended destination
            return redirect()->intended('/dashboard')->with('success', 'Two-factor authentication successful.');
        }

        return back()->withErrors(['2fa_code' => 'The provided two-factor authentication code is incorrect.']);
    }

    public function show()
    {
        return view('auth.two-factor-challenge');
    }

    // show two factor authentication challenge by recovering code
    public function showRecoveryForm()
    {
        return view('auth.two-factor-recovery');
    }

    // verify two factor authentication by recovery code
    public function verifyRecovery(Request $request)
    {
        $request->validate([
            'recovery_code' => ['required', 'string'],
        ]);

        $user = $request->user();

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (! in_array($request->recovery_code, $recoveryCodes)) {
            return back()->withErrors(['recovery_code' => 'The provided recovery code is incorrect.']);
        }

        // Mark the user as having passed the 2FA challenge
        $request->session()->put('two_factor_authenticated', true);

        // Redirect the user to their intended destination
        return redirect()->intended('/dashboard')->with('success', 'Two-factor authentication successful.');
    }
}
