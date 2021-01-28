<?php

use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->country,
        'code' => $faker->unique()->country
    ];
});
