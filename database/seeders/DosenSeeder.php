<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = [
            [
                'nidn' => '0412345601',
                'nama' => 'Dr. Ahmad Fauzi, M.Kom.',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'nidn' => '0423456702',
                'nama' => 'Siti Rahmawati, M.T.',
                'prodi' => 'Sistem Informasi',
            ],
            [
                'nidn' => '0434567803',
                'nama' => 'Budi Santoso, Ph.D.',
                'prodi' => 'Teknik Elektro',
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::updateOrCreate(
                ['nidn' => $dosen['nidn']],
                ['nama' => $dosen['nama'], 'prodi' => $dosen['prodi']],
            );
        }
    }
}
