<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Episode;
use Faker\Generator as Faker;

$factory->define(Episode::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->words(3, true),
        'season' => $faker->numberBetween(1, 6),
        'episode' => $faker->numberBetween(0, 320)
    ];
});
