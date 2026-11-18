<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return $this->redirectToDashboard($request->user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if ($request->user()) {
            return $this->redirectToDashboard($request->user());
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password yang dimasukkan salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $request->user();
        if (! in_array($user->role, ['admin', 'dosen', 'mahasiswa'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('login')
                ->withErrors(['email' => 'Akun ini belum memiliki role yang valid.']);
        }

        return $this->redirectToDashboard($user);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }

    private function redirectToDashboard(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => to_route('admin'),
            'dosen' => to_route('dosen'),
            'mahasiswa' => to_route('mahasiswa'),
            default => abort(403, 'Akun ini belum memiliki role yang valid.'),
        };
    }
}
