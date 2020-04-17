<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->addReminder([
            'before_amount' => 0,
            'before_unit' => 1,
        ]);
    }
}
