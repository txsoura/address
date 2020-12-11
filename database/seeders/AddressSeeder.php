<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::create([
            'name' => 'Brasil',
            'code' => 'BRA'
        ]);

        $state = State::create([
            'name' => 'Paraná',
            'code' => 'PR',
            'country_id' => $country->id
        ]);

        City::create([
            'name' => 'Foz do Iguaçu',
            'code' => 'IGU',
            'state_id' => $state->id
        ]);
    }
}
