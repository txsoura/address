<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'street' => $this->faker->streetName,
            'postcode' => $this->faker->buildingNumber,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'district' => $this->faker->cityPrefix,
            'name' => $this->faker->citySuffix,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'user_id' =>  $this->faker->randomDigit,
            'city_id' => City::factory()
        ];
    }
}
