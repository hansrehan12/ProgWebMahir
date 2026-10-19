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
            ['kode' => 'TIF101', 'nama' => 'Pemrograman Lanjut', 'sks' => 3, 'nidn' => '0012058401'],
            ['kode' => 'SIS201', 'nama' => 'Perancangan Basis Data', 'sks' => 4, 'nidn' => '0023118602'],
            ['kode' => 'TIF203', 'nama' => 'Jaringan & Keamanan Komputer', 'sks' => 3, 'nidn' => '0015098803'],
            ['kode' => 'SDA301', 'nama' => 'Machine Learning & AI', 'sks' => 3, 'nidn' => '0008048204'],
            ['kode' => 'RPL202', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'nidn' => '0019018705'],
            ['kode' => 'SIS102', 'nama' => 'Analisis & Perancangan Sistem', 'sks' => 3, 'nidn' => '0027038906'],
            ['kode' => 'TKO201', 'nama' => 'Arsitektur Sistem Komputer', 'sks' => 3, 'nidn' => '0005128307'],
            ['kode' => 'SDA202', 'nama' => 'Visualisasi Data & Big Data', 'sks' => 3, 'nidn' => '0014078508'],
            ['kode' => 'TIF305', 'nama' => 'Algoritma & Struktur Data', 'sks' => 4, 'nidn' => '0021108809'],
            ['kode' => 'RPL304', 'nama' => 'Pengujian Perangkat Lunak', 'sks' => 2, 'nidn' => '0003068110'],
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
