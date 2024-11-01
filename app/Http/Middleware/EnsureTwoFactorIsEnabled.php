<?php

// app/Http/Middleware/EnsureTwoFactorIsEnabled.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorIsEnabled
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // If the user has two-factor authentication enabled but is not authenticated with a 2FA challenge
        if ($user && $user->two_factor_secret && !$request->session()->get('two_factor_authenticated')) {
            return redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}
