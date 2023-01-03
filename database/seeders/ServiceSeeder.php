<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;
use Faker\Generator as Faker;
use DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $name = Str::random(10);
        $description = Str::random(10);

        for ($i = 1; $i < 10; $i++) {
            DB::table('services')->insert([
                'id' => $i,
                'name' => $faker->name,
                'description' => $faker->name,
            ]);
        }
    }
}
