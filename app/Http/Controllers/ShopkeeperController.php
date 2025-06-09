<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopkeeper;
use Illuminate\Support\Facades\Hash;

class ShopkeeperController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:shopkeepers,UserName|min:3',
            'password' => 'required|min:6|confirmed',
        ]);

        $shopkeeper = Shopkeeper::create([
            'UserName' => $request->username,
            'Password' => Hash::make($request->password),
        ]);

        Auth::login($shopkeeper);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to BERWA SHOP.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['UserName' => $credentials['username'], 'Password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 