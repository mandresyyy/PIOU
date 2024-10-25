<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCtrl extends Controller
{
    public function login()
    {
        return view('pages.users.login');
    }

    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        $remember = $payload['remember'] ?? false;

        unset($payload['remember']);

        if (Auth::attempt($payload, $remember)) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return redirect()
            ->route('login')
            ->withErrors(__('Nous ne pouvons pas vous autoriser à vous connecter'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function inscription()
    {
        return view('pages.users.inscription');
    }

    public function saveUser(Request $request)
    {
        $validate = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email',
            're_pass' => 'required|same:pass',
            'pass' => 'required|min:6',
        ]);

        // créer un nouvel utilisateur
        User::create([
            'firstname' => $request->prenom,
            'lastname' => $request->nom,
            'email' => $request->email,
            'password' => bcrypt($request->pass),
        ]);

        return redirect()->back()->with('success', 'Utilisateur enregistré avec succès.');
    }
}
