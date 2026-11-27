<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PassengerLoginRequest;
use App\Http\Requests\Auth\PassengerRegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PassengerAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.passenger-login');
    }

    public function login(PassengerLoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->role !== 'passenger') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki peran yang valid. Silakan hubungi administrator.',
                ]);
            }

            return redirect()->intended('/jadwal');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan password tidak cocok.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('auth.passenger-register');
    }

    public function register(PassengerRegisterRequest $request): RedirectResponse
    {
        $user = new User([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'password' => Hash::make($request->validated('password')),
        ]);
        // Explicitly set role because role is mass-assignment protected
        $user->forceFill(['role' => 'passenger'])->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/jadwal')->with('success', 'Pendaftaran berhasil. Selamat datang di LintasGo!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
