<?php

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
            factory(\App\Models\Country::class, 4)->create();
            factory(\App\Models\State::class, 3)->create();
            factory(\App\Models\City::class, 2)->create();
            factory(\App\Models\Address::class, 1)->create();
        }
    }
}
