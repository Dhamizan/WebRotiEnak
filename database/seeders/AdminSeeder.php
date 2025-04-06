<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('penggunas')->insert([
            'nama' => 'Admin',
            'email' => 'dhamizanputra@gmail.com',
            'notelp' => '08123456789',
            'alamat' => 'Griya Soka Bogor',
            'gerai' => null,
            'kata_sandi' => bcrypt('admin123'),
            'peran' => 1,
            'jenis_kelamin' => 'Pria',
            'id_sidik_jari' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
