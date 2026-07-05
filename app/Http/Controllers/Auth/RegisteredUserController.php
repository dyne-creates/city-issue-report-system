<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $barangays = DB::table('barangays')->orderBy('name')->get();

        return view('auth.register', compact('barangays'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:users,email'],
            'contact_number' => ['required', 'string', 'max:20'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'barangay_id' => $request->barangay_id,
            'password' => Hash::make($request->password),
            'role' => 'citizen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Auth::loginUsingId($userId);

        event(new Registered(Auth::user()));

        return redirect()->route('citizen.dashboard');
    }
}
