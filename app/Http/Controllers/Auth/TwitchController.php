<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class TwitchController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return Socialite::driver('twitch')
            ->scopes(['user:read:email'])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        $twitchUser = Socialite::driver('twitch')->user();

        $user = User::updateOrCreate([
            'twitch_id' => $twitchUser->id,
        ], [
            'name' => $twitchUser->name,
            'email' => $twitchUser->email,
            'twitch_token' => $twitchUser->token,
            'twitch_refresh_token' => $twitchUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
