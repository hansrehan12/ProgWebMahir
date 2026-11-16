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
                'email' => 'budi.santoso@kampus.ac.id',
                'nohp' => '081234560001',
            ],
            [
                'nidn' => '0023118602',
                'nama' => 'Prof. Dr. Anita Wijaya, M.Kom.',
                'prodi' => 'Sistem Informasi',
                'email' => 'anita.wijaya@kampus.ac.id',
                'nohp' => '081234560002',
            ],
            [
                'nidn' => '0015098803',
                'nama' => 'Hendra Pratama, S.T., M.T.',
                'prodi' => 'Teknik Informatika',
                'email' => 'hendra.pratama@kampus.ac.id',
                'nohp' => '081234560003',
            ],
            [
                'nidn' => '0008048204',
                'nama' => 'Dr. Rina Kusuma, S.Kom., M.C.S.',
                'prodi' => 'Sains Data',
                'email' => 'rina.kusuma@kampus.ac.id',
                'nohp' => '081234560004',
            ],
            [
                'nidn' => '0019018705',
                'nama' => 'Ahmad Fauzi, M.T., Ph.D.',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'email' => 'ahmad.fauzi@kampus.ac.id',
                'nohp' => '081234560005',
            ],
            [
                'nidn' => '0027038906',
                'nama' => 'Dewi Lestari, S.Kom., M.S.I.',
                'prodi' => 'Sistem Informasi',
                'email' => 'dewi.lestari@kampus.ac.id',
                'nohp' => '081234560006',
            ],
            [
                'nidn' => '0005128307',
                'nama' => 'Ir. Bambang Hariyanto, M.T.',
                'prodi' => 'Teknik Komputer',
                'email' => 'bambang.hariyanto@kampus.ac.id',
                'nohp' => '081234560007',
            ],
            [
                'nidn' => '0014078508',
                'nama' => 'Siti Rahmawati, S.T., M.Eng.',
                'prodi' => 'Sains Data',
                'email' => 'siti.rahmawati@kampus.ac.id',
                'nohp' => '081234560008',
            ],
            [
                'nidn' => '0021108809',
                'nama' => 'Dr. Eko Purwanto, M.Kom.',
                'prodi' => 'Teknik Informatika',
                'email' => 'eko.purwanto@kampus.ac.id',
                'nohp' => '081234560009',
            ],
            [
                'nidn' => '0003068110',
                'nama' => 'Maya Putri, S.Kom., M.T.',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'email' => 'maya.putri@kampus.ac.id',
                'nohp' => '081234560010',
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::updateOrCreate(
                ['nidn' => $dosen['nidn']],
                [
                    'nama' => $dosen['nama'],
                    'prodi' => $dosen['prodi'],
                    'email' => $dosen['email'],
                    'nohp' => $dosen['nohp'],
                ],
            );
        }
    }
}
