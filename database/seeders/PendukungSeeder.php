<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Wilayah\Kelurahan;

class PendukungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $kelurahan = Kelurahan::where('kecamatan_id', '32.73.25')->pluck('id');
    	for($i = 1; $i <= 20; $i++){
 
    		DB::table('pendukung')->insert([
    			'nik' => $faker->nik(),
    			'nama' => $faker->name,
    			'jk' => $faker->jobTitle,
    			'tmpLahir' => 'Bandung',
    			'tglLahir' => $faker->dateTimeBetween('1990-01-01', '2012-12-31')->format('Y-m-d'),
                'alamat' => $faker->address,
    			'rt' => $faker->randomDigitNotNull(),
                'rw' => $faker->randomDigitNotNull(),
    			'kelurahan_id' => $faker->randomElement($kelurahan),
                'kecamatan_id' => '32.73.25',
    			'kota_id' => '32.73',
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'user_id' => 23
    		]);
 
    	}
    }
}
