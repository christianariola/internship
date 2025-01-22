<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    // @desc   Show the registration form
    // @route  GET /register
    public function register(): View
    {
        return view('auth.register');
    }

    // @desc   Store user to database
    // @route  POST /register
    public function store(): RedirectResponse
    {
        $validatedData = request()->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hash the password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create the user
        $user = User::create($validatedData);

        return redirect()->route('login')->with('success', 'You are registered and can login!');
    }
}