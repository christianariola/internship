<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // @desc   Show the login form
    // @route  GET /login
    public function login(): View
    {
        return view('auth.login');
    }

    // @desc   Authenticate user
    // @route  POST /login
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = request()->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'You are now logged in!');
        }

        // If auth fails, redurect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // @desc   Logout user
    // @route  POST /logout
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You are now logged out!');
    }
}