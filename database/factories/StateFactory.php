<?php

use App\Models\State;
use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->state,
        'code' => $faker->unique()->stateAbbr,
        'country_id' => function () {
            return factory(Country::class)->create()->id;
        }
    ];
});
