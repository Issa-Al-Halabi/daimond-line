<?php

namespace App\Observers;

use App\Model\User;

class UserLoginObserver
{
    //
    public function login(User $user)
    {
        Log::info('User logged in', ['user_id' => $user->id, 'email' => $user->email]);
    }
}
