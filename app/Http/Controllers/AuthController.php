<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'email không được để trống',
            'password.required' => 'Password không được để trống',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withInput()->with('error', 'Username hoặc password không đúng');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
