<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MataKuliahCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_mata_kuliah_can_be_created_updated_viewed_and_deleted(): void
    {
        $dosen = Dosen::create([
            'nidn' => '4444444444',
            'nama' => 'Dosen Pengampu',
            'prodi' => 'Informatika',
        ]);

        $this->post(route('mata-kuliah.store'), [
            'kode' => 'MK101',
            'nama' => 'Pemrograman Dasar',
            'sks' => 3,
            'dosen_id' => $dosen->id,
        ])->assertRedirect(route('mata-kuliah.index'));

        $mataKuliah = MataKuliah::where('kode', 'MK101')->firstOrFail();
        $this->assertDatabaseHas('mata_kuliah', ['id' => $mataKuliah->id, 'dosen_id' => $dosen->id]);

        $this->get(route('mata-kuliah.index'))
            ->assertOk()
            ->assertSee('MK101')
            ->assertSee('Dosen Pengampu')
            ->assertSee('cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css')
            ->assertSee('code.jquery.com/jquery-3.7.1.min.js')
            ->assertSee("$('#mata-kuliah-table').DataTable({", false);

        $this->get(route('mata-kuliah.show', $mataKuliah))
            ->assertOk()
            ->assertSee('Pemrograman Dasar');
        $this->get(route('mata-kuliah.edit', $mataKuliah))
            ->assertOk()
            ->assertSee('value="MK101"', false);

        $this->put(route('mata-kuliah.update', $mataKuliah), [
            'kode' => $mataKuliah->kode,
            'nama' => 'Pemrograman Lanjut',
            'sks' => 4,
            'dosen_id' => '',
        ])->assertRedirect(route('mata-kuliah.index'));
        $this->assertDatabaseHas('mata_kuliah', [
            'id' => $mataKuliah->id,
            'nama' => 'Pemrograman Lanjut',
            'dosen_id' => null,
        ]);

        $this->delete(route('mata-kuliah.destroy', $mataKuliah))
            ->assertRedirect(route('mata-kuliah.index'));
        $this->assertDatabaseMissing('mata_kuliah', ['id' => $mataKuliah->id]);
    }

    public function test_mata_kuliah_validation_preserves_old_input(): void
    {
        $this->from(route('mata-kuliah.create'))
            ->post(route('mata-kuliah.store'), [
                'kode' => 'VALIDASI1',
                'nama' => 'Mata Kuliah Lama',
                'sks' => 0,
                'dosen_id' => 999,
            ])
            ->assertRedirect(route('mata-kuliah.create'))
            ->assertSessionHasErrors(['sks', 'dosen_id']);

        $this->get(route('mata-kuliah.create'))
            ->assertOk()
            ->assertSee('value="Mata Kuliah Lama"', false);
    }
}
