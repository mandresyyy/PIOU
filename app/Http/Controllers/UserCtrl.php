<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            // créer un nouvel utilisateur
            $user = User::create([
                'firstname' => $request->prenom,
                'lastname' => $request->nom,
                'email' => $request->email,
                'password' => bcrypt($request->pass),
            ]);

            // feeds par defaut
            $defaultFeeds = [
                [
                    'name' => 'Actualités Tech',
                    'url' => 'https://www.simplilearn.com/top-technology-trends-and-jobs-article',
                    'favicon' => 'https://cdn4.iconfinder.com/data/icons/modern-technologies-1/32/technology_Block_blockchain_chain_cryptocurrency-04-64.png',
                    'link' => [
                        'title' => 'Top Technology Trends',
                        'url' => 'https://www.simplilearn.com/top-technology-trends-and-jobs-article',
                        'published_at' => now(),
                    ],
                ],
                [
                    'name' => 'Blog de Cuisine',
                    'url' => 'https://www.cuisineactuelle.fr/',
                    'favicon' => 'https://cdn2.iconfinder.com/data/icons/chinese-food-line/64/chinese_food_meal_cuisine_moon_cake-64.png',
                    'link' => [
                        'title' => 'Recette de Gâteau au Chocolat',
                        'url' => 'https://www.cuisineactuelle.fr/recettes/gateau-au-chocolat-214408',
                        'published_at' => now(),
                    ],
                ],
                [
                    'name' => 'Voyages',
                    'url' => 'https://fr.euronews.com/voyages',
                    'favicon' => 'https://cdn2.iconfinder.com/data/icons/summer-178/128/_Air_earth_fly_planet_vacation_voyage-64.png',
                    'link' => [
                        'title' => 'Meilleures Destinations de Voyage 2024',
                        'url' => 'https://fr.euronews.com/voyages/2023/10/01/meilleures-destinations-de-voyage-2024',
                        'published_at' => now(),
                    ],
                ],
            ];

            foreach ($defaultFeeds as $feedData) {
                // Créer et sauvegarder le feed
                $feed = new Feed();
                $feed->user_id = $user->id; // Attribuer au nouvel utilisateur
                $feed->name = $feedData['name'];
                $feed->url = $feedData['url'];
                $feed->favicon = $feedData['favicon'];
                $feed->created_at = now();
                $feed->updated_at = now();
                $feed->checked_at = null;
                $feed->save();

                // Créer et sauvegarder le link associé
                $feed->links()->create([
                    'user_id' => $user->id, // Attribuer au nouvel utilisateur
                    'title' => $feedData['link']['title'],
                    'url' => $feedData['link']['url'],
                    'published_at' => $feedData['link']['published_at'],
                    'content' => 'COntent',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'read_at' => null, // Initialiser comme non lu
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->back()->with('success', 'Utilisateur enregistré avec succès.');
    }
}
