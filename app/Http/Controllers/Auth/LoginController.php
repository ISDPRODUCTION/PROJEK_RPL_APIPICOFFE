<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    //public function login(Request $request): RedirectResponse
    //{
       // $credentials = $request->validate([
            //'email'    => 'required|string',
           // 'password' => 'required|string',
       // ]);

       // $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        //if (Auth::attempt([$fieldType => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
        //    $request->session()->regenerate();
//
       //     Auth::user()->update(['shift_started_at' => Carbon::now()]);

       //     return redirect()->intended(route('pos.index'));
       // }

        //return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    //}

    public function logout(Request $request): RedirectResponse
    {
        Auth::user()?->update(['shift_started_at' => null]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        \Log::info('Login attempt', ['field' => $fieldType, 'value' => $credentials['email']]);

        if (Auth::attempt([$fieldType => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            \Log::info('Login success, regenerating session');
            $request->session()->regenerate();
            \Log::info('Session regenerated, redirecting');
            return redirect()->intended(route('pos.index'));
        }

        \Log::info('Login failed');
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }
}