<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\State;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'device' => $faker->name,
        'os' => $faker->randomElement(['Windows', 'Linux', 'Android', 'IOS']),
        'memory' => $faker->numberBetween(1024, 2048),
        'storage' => $faker->numberBetween(20420, 80450)
    ];
});
