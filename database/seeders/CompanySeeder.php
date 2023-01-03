<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
use DB;
use Faker\Generator as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {

        // function (Faker $faker) {
        //     return [
        //         'username' => $faker->name,
        //         'email' => $faker->unique()->email,
        //         'remember_token' => str_random(60),
        //         'password' => $faker->sha1, // secret
        //         'remember_token' => str_random(10)
        //     ];
        // }

        for ($i = 1; $i < 10; $i++) {

            DB::table('companies')->insert([
                'id' => $i,
                'company_name' => $faker->name,
                'company_email' => $faker->unique()->email,
                'country_id' =>  rand(1, 10),
                'service_id' =>  rand(1, 10),
                'user_id' => 1
            ]);
        }
    }
}
