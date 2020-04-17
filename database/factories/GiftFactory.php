<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Birthday;
use App\Models\Gift;
use Faker\Generator as Faker;

$factory->define(Gift::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'birthday_id' => factory(Birthday::class),
    ];
});
