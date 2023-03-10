<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
use DB;
use Faker\Generator as Faker;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $country = Str::random(10);

        for ($i = 1; $i < 10; $i++) {
            DB::table('countries')->insert([
                'id' => $i,
                'name' => $faker->name,
            ]);
        }
    }
}
