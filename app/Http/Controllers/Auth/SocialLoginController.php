<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Socialite;

class SocialLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('social');
    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callback($service)
    {
        try {
            $serviceUser = Socialite::driver($service)->stateless()->user();
        } catch (\Exception $e) {
            return redirect(config('app.client_url') . '/auth/social?error=Unable to login using ' . $service .
                '. Please try again' . '&origin=login');
        }

        $email = $serviceUser->getEmail();

        $user = $this->getExistingUser($serviceUser, $email, $service);
        $newUser = false;
        if (!$user) {
            $newUser = true;
            $user = User::create([
                'name' => $serviceUser->getName(),
                'email' => $email,
                'password' => '',
            ]);
        }

        if ($this->needsToCreateSocial($user, $service)) {
            $user->social_login()->create([
                'user_id' => $user->id,
                'social_id' => $serviceUser->getId(),
                'service' => $service,
            ]);
        }

        return redirect(config('app.client_url') . '/auth/social?token=' . auth()->fromUser($user) . '&origin=' .
            ($newUser ? 'register' : 'login'));
    }

    public function needsToCreateSocial(User $user, $service)
    {
        return !$user->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email, $service)
    {
        return User::where('email', $email)->orWhereHas('social_login', function ($q) use ($serviceUser, $service) {
            $q->where('social_id', $serviceUser->getId())->where('service', $service);
        })->first();
    }
}
