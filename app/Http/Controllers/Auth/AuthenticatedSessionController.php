<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

<<<<<<< HEAD
        if(Auth::user()->role == 'admin'){
            return redirect(route('admin.dashboard'));
        } elseif (Auth::user()->role == 'staff') {
            return redirect(route('staff.dashboard'));
        }

        return redirect()->intended(route('citizen.dashboard', absolute: false));
=======
        

        return redirect()->intended(route('dashboard', absolute: false));
>>>>>>> d97a5ac3780dcf270d7a5b2cc879f28b8a482c15
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
