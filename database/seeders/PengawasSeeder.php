<?php

namespace Database\Seeders;

use App\Models\Pengawas;
use Illuminate\Database\Seeder;

class PengawasSeeder extends Seeder
{
    public function run(): void
    {
        $pengawas = [
            [
                'nama' => 'Andi Pratama',
                'alamat' => 'Jl. Merdeka No. 10',
                'notelp' => '081234567890',
                'email' => 'andi.pratama@example.com',
            ],
            [
                'nama' => 'Siti Aminah',
                'alamat' => 'Jl. Pendidikan No. 25',
                'notelp' => '081298765432',
                'email' => 'siti.aminah@example.com',
            ],
            [
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Kampus No. 5',
                'notelp' => '082112223333',
                'email' => 'budi.santoso@example.com',
            ],
            [
                'nama' => 'Dewi Anggraini',
                'alamat' => 'Jl. Melati No. 18',
                'notelp' => '081355667788',
                'email' => 'dewi.anggraini@example.com',
            ],
            [
                'nama' => 'Rizky Maulana',
                'alamat' => 'Jl. Sudirman No. 42',
                'notelp' => '082133445566',
                'email' => 'rizky.maulana@example.com',
            ],
            [
                'nama' => 'Nurul Hidayah',
                'alamat' => 'Jl. Kenanga No. 7',
                'notelp' => '085712345678',
                'email' => 'nurul.hidayah@example.com',
            ],
            [
                'nama' => 'Fajar Setiawan',
                'alamat' => 'Jl. Diponegoro No. 31',
                'notelp' => '081277889900',
                'email' => 'fajar.setiawan@example.com',
            ],
            [
                'nama' => 'Rina Marlina',
                'alamat' => 'Jl. Mawar No. 12',
                'notelp' => '083812345678',
                'email' => 'rina.marlina@example.com',
            ],
            [
                'nama' => 'Agus Wijaya',
                'alamat' => 'Jl. Veteran No. 55',
                'notelp' => '081988776655',
                'email' => 'agus.wijaya@example.com',
            ],
            [
                'nama' => 'Lestari Wulandari',
                'alamat' => 'Jl. Cendana No. 3',
                'notelp' => '085612398765',
                'email' => 'lestari.wulandari@example.com',
            ],
        ];

        foreach ($pengawas as $data) {
            Pengawas::updateOrCreate(
                ['email' => $data['email']],
                $data,
            );
        }
    }
}
