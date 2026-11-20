<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'ends_with:@student.ub.ac.id',
            ],
            'fakultas' => ['required', 'string', 'max:100'],
            'program_studi' => ['required', 'string', 'max:100'],
            'angkatan' => ['required', 'digits:4'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->numbers(),
            ],
        ], [
            'email.ends_with' => 'Pendaftaran wajib menggunakan email mahasiswa UB (@student.ub.ac.id).',
            'password.min' => 'Kata sandi minimal harus terdiri dari 8 karakter.',
            'password.numbers' => 'Kata sandi wajib mengandung setidaknya 1 angka.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'fakultas' => $validated['fakultas'],
            'program_studi' => $validated['program_studi'],
            'angkatan' => $validated['angkatan'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Akun berhasil dibuat!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}