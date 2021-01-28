<?php

use App\Models\City;
use App\Models\State;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->city,
        'code' => $faker->unique()->cityPrefix,
        'state_id' => function () {
            return factory(State::class)->create()->id;
        }
    ];
});
