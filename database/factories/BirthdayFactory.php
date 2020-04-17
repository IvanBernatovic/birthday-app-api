<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Birthday;
use App\User;
use Faker\Generator as Faker;

$factory->define(Birthday::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'date' => $faker->date(),
        'user_id' => factory(User::class),
    ];
});
