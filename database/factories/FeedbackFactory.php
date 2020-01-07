<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feedback;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'company_token' => $faker->company,
        'priority' => $faker->randomElement(['minor', 'major', 'central'])
    ];
});
