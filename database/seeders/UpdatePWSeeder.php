<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class UpdatePWSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::where('email', 'dhamizanputra@gmail.com')->update([
            'kata_sandi' => Hash::make('123'),
        ]);
    }
}
