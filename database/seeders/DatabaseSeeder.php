<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AddressSeeder::class);

        if (App::environment('local', 'staging')) {
            \App\Models\Country::factory(4)->create();
            \App\Models\State::factory(3)->create();
            \App\Models\City::factory(2)->create();
            \App\Models\Address::factory()->create();
        }
    }
}
