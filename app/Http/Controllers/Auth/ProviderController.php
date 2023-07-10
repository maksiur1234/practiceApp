<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        try {
            /** @var User $socialUser */
            $socialUser = Socialite::driver($provider)->user();

            if(User::query()->where('email', $socialUser->getEmail())->exists()){
                return redirect('/login')->withErrors(['email' => 'This email uses different method to login!']);
            }
            $user = User::where([
                'provider' => $provider,
                'provider_id' => $socialUser->id,
            ])->first();
            if(!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => $socialUser->password,
                    'username' => User::generateUserName($socialUser->getNickname()),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                    'email_verified_at' => now()
                ]);
            }
            Auth::login($user);
            return redirect('/home');
        } catch (\Exception $e){
            return redirect('/login');
        }

    }
}
