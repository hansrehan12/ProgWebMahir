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
                'nidn' => '0012058401',
                'nama' => 'Dr. Eng. Budi Santoso, M.T.',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'nidn' => '0023118602',
                'nama' => 'Prof. Dr. Anita Wijaya, M.Kom.',
                'prodi' => 'Sistem Informasi',
            ],
            [
                'nidn' => '0015098803',
                'nama' => 'Hendra Pratama, S.T., M.T.',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'nidn' => '0008048204',
                'nama' => 'Dr. Rina Kusuma, S.Kom., M.C.S.',
                'prodi' => 'Sains Data',
            ],
            [
                'nidn' => '0019018705',
                'nama' => 'Ahmad Fauzi, M.T., Ph.D.',
                'prodi' => 'Rekayasa Perangkat Lunak',
            ],
            [
                'nidn' => '0027038906',
                'nama' => 'Dewi Lestari, S.Kom., M.S.I.',
                'prodi' => 'Sistem Informasi',
            ],
            [
                'nidn' => '0005128307',
                'nama' => 'Ir. Bambang Hariyanto, M.T.',
                'prodi' => 'Teknik Komputer',
            ],
            [
                'nidn' => '0014078508',
                'nama' => 'Siti Rahmawati, S.T., M.Eng.',
                'prodi' => 'Sains Data',
            ],
            [
                'nidn' => '0021108809',
                'nama' => 'Dr. Eko Purwanto, M.Kom.',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'nidn' => '0003068110',
                'nama' => 'Maya Putri, S.Kom., M.T.',
                'prodi' => 'Rekayasa Perangkat Lunak',
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
