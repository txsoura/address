<?php

use App\Models\Address;
use App\Models\City;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'street' => $faker->streetName,
        'postcode' => $faker->buildingNumber,
        'number' => $faker->buildingNumber,
        'complement' => $faker->secondaryAddress,
        'district' => $faker->cityPrefix,
        'name' => $faker->citySuffix,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'owner_type' =>  'users',
        'owner_id' =>  $faker->randomDigit,
        'city_id' => function () {
            return factory(City::class)->create()->id;
        }
    ];
});
