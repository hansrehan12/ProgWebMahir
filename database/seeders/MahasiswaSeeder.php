<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        Mahasiswa::factory()->count(50)->create();

        Mahasiswa::create([
            'nim'   => '240001',
            'nama'  => 'Andi',
            'prodi' => 'Informatika',
            'nohp'  => '081287878787',
            'email' => 'andi@gmail.com',
        ]);
    }
}