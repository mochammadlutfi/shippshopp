<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use DB;
class TestimoniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
    	for($i = 1; $i <= 10; $i++){
 
    		DB::table('testimoni')->insert([
    			'nama' => $faker->name,
    			'pekerjaan' => $faker->jobTitle,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'deskripsi' => '0',
    		]);
 
    	}
    }
}
