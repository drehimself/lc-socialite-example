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
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::firstOrCreate(
            [
                'provider_id' => $githubUser->getId(),
            ],
            [
                'email' => $githubUser->getEmail(),
                'name' => $githubUser->getName(),
            ]
        );

        // $user = User::where('provider_id', $githubUser->getId())->first();

        // // Create a new user in our database
        // if (! $user) {
        //     $user = User::create([
        //         'email' => $githubUser->getEmail(),
        //         'name' => $githubUser->getName(),
        //         'provider_id' => $githubUser->getId(),
        //     ]);
        // }

        // Log the user in
        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('dashboard');
    }
}
