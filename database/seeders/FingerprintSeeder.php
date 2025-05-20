<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FingerprintSeeder extends Seeder
{
    public function run()
    {
        DB::table('penggunas')->insert([
            'nama' => 'Fingerprint',
            'email' => 'FingerprintSensor_RT@gmail.com',
            'notelp' => '08123456789',
            'alamat' => 'Roti Enak',
            'gerai_id' => null,
            'kata_sandi' => bcrypt('fing1234'),
            'peran' => 3,
            'jenis_kelamin' => 'Pria',
            'id_sidik_jari' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
