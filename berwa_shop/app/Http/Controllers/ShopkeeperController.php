<?php

namespace App\Http\Controllers;

use App\Models\Shopkeeper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ShopkeeperController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:shopkeepers'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $shopkeeper = new Shopkeeper();
        $shopkeeper->name = $validated['name'];
        $shopkeeper->email = $validated['email'];
        $shopkeeper->password = Hash::make($validated['password']);
        $shopkeeper->save();

        Auth::guard('web')->login($shopkeeper);

        return redirect()->route('dashboard')->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
