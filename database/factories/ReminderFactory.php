<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Birthday;
use App\Models\Reminder;
use App\User;
use Faker\Generator as Faker;

$factory->define(Reminder::class, function (Faker $faker) {
    return [
        'before_amount' => random_int(0, 10),
        'before_unit' => random_int(1, 2),
    ];
});

$factory->state(Reminder::class, 'birthday', function ($faker) {
    return [
        'remindable_type' => 1,
        'remindable_id' => factory(Birthday::class),
    ];
});

$factory->state(Reminder::class, 'global', function ($faker) {
    return [
        'remindable_type' => 2,
        'remindable_id' => factory(User::class),
    ];
});
