<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $socialiteUser = Socialite::driver($provider)->user();

        $user = User::firstOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
            ],
            [
                'email' => $socialiteUser->getEmail(),
                'name' => $socialiteUser->getName(),
            ]
        );

        // $user = User::where('provider_id', $socialiteUser->getId())->first();

        // // Create a new user in our database
        // if (! $user) {
        //     $user = User::create([
        //         'email' => $socialiteUser->getEmail(),
        //         'name' => $socialiteUser->getName(),
        //         'provider_id' => $socialiteUser->getId(),
        //     ]);
        // }

        // Log the user in
        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('dashboard');
    }
}
