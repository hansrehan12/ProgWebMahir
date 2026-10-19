<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $mataKuliah = [
            ['kode' => 'IF101', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'nidn' => '0412345601'],
            ['kode' => 'IF102', 'nama' => 'Struktur Data dan Algoritma', 'sks' => 4, 'nidn' => '0412345601'],
            ['kode' => 'IF103', 'nama' => 'Basis Data Lanjut', 'sks' => 3, 'nidn' => '0412345601'],
            ['kode' => 'SI201', 'nama' => 'Analisis dan Perancangan Sistem', 'sks' => 3, 'nidn' => '0423456702'],
            ['kode' => 'SI202', 'nama' => 'Sistem Informasi Manajemen', 'sks' => 3, 'nidn' => '0423456702'],
            ['kode' => 'TE301', 'nama' => 'Rangkaian Elektrik', 'sks' => 3, 'nidn' => '0434567803'],
            ['kode' => 'TE302', 'nama' => 'Elektronika Dasar', 'sks' => 4, 'nidn' => '0434567803'],
        ];

        foreach ($mataKuliah as $mataKuliahData) {
            $dosen = Dosen::where('nidn', $mataKuliahData['nidn'])->firstOrFail();

            MataKuliah::updateOrCreate(
                ['kode' => $mataKuliahData['kode']],
                [
                    'nama' => $mataKuliahData['nama'],
                    'sks' => $mataKuliahData['sks'],
                    'dosen_id' => $dosen->id,
                ],
            );
        }
    }
}
