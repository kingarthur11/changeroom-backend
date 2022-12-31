<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
use DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Str::random(10);

        for($i=1; $i<10; $i++){
            DB::table('countries')->insert([
                'id' => $i,
                'name' => $country
            ]);
        }
    }
}
