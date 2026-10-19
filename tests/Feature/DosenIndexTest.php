<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_page_displays_dosen_and_their_mata_kuliah(): void
    {
        $dosen = Dosen::create([
            'nidn' => '0412345601',
            'nama' => 'Dr. Ahmad Fauzi, M.Kom.',
            'prodi' => 'Teknik Informatika',
        ]);

        MataKuliah::create([
            'kode' => 'IF101',
            'nama' => 'Pemrograman Berorientasi Objek',
            'sks' => 3,
            'dosen_id' => $dosen->id,
        ]);

        $this->get('/getDosen')
            ->assertOk()
            ->assertSee('0412345601')
            ->assertSee('Dr. Ahmad Fauzi, M.Kom.')
            ->assertSee('Teknik Informatika')
            ->assertSee('IF101')
            ->assertSee('Pemrograman Berorientasi Objek');
    }

    public function test_database_seeder_adds_dosens_and_mata_kuliah_idempotently(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('dosen', 3);
        $this->assertDatabaseCount('mata_kuliah', 7);
        $this->assertDatabaseHas('mata_kuliah', [
            'kode' => 'IF101',
            'dosen_id' => Dosen::where('nidn', '0412345601')->value('id'),
        ]);
    }
}
