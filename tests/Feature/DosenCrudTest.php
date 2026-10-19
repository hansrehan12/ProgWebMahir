<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_can_be_created_updated_soft_deleted_restored_and_force_deleted(): void
    {
        $response = $this->post(route('dosen.store'), [
            'nidn' => '1111111111',
            'nama' => 'Dosen Baru',
            'prodi' => 'Teknik Informatika',
        ]);

        $dosen = Dosen::where('nidn', '1111111111')->firstOrFail();
        $response->assertRedirect(route('dosen.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('dosen', ['id' => $dosen->id, 'nama' => 'Dosen Baru']);
        $this->get(route('dosen.show', $dosen))->assertOk()->assertSee('Dosen Baru');
        $this->get(route('dosen.edit', $dosen))->assertOk()->assertSee('value="1111111111"', false);

        $this->put(route('dosen.update', $dosen), [
            'nidn' => $dosen->nidn,
            'nama' => 'Nama Diperbarui',
            'prodi' => 'Sistem Informasi',
        ])->assertRedirect(route('dosen.index'));
        $this->assertDatabaseHas('dosen', ['id' => $dosen->id, 'nama' => 'Nama Diperbarui']);

        $mataKuliah = MataKuliah::create([
            'kode' => 'ZZ101',
            'nama' => 'Kelas Dosen',
            'sks' => 3,
            'dosen_id' => $dosen->id,
        ]);

        $this->delete(route('dosen.destroy', $dosen))->assertRedirect(route('dosen.index'));
        $this->assertSoftDeleted('dosen', ['id' => $dosen->id]);
        $this->assertDatabaseHas('mata_kuliah', ['id' => $mataKuliah->id]);
        $this->get(route('dosen.trash'))->assertOk()->assertSee('Nama Diperbarui');

        $this->post(route('dosen.restore', $dosen->id))->assertRedirect(route('dosen.trash'));
        $this->assertDatabaseHas('dosen', ['id' => $dosen->id, 'deleted_at' => null]);

        $this->delete(route('dosen.destroy', $dosen));
        $this->delete(route('dosen.forceDelete', $dosen->id))->assertRedirect(route('dosen.trash'));
        $this->assertDatabaseMissing('dosen', ['id' => $dosen->id]);
        $this->assertDatabaseMissing('mata_kuliah', ['id' => $mataKuliah->id]);
    }

    public function test_dosen_validation_keeps_old_input_and_update_ignores_current_nidn(): void
    {
        $existing = Dosen::create([
            'nidn' => '2222222222',
            'nama' => 'Dosen Lama',
            'prodi' => 'Teknik Informatika',
        ]);

        $this->from(route('dosen.create'))
            ->post(route('dosen.store'), [
                'nidn' => $existing->nidn,
                'nama' => 'Nama Lama',
                'prodi' => '',
            ])
            ->assertRedirect(route('dosen.create'))
            ->assertSessionHasErrors(['nidn', 'prodi']);

        $this->get(route('dosen.create'))
            ->assertOk()
            ->assertSee('value="Nama Lama"', false);

        $this->put(route('dosen.update', $existing), [
            'nidn' => $existing->nidn,
            'nama' => 'Dosen Lama Diperbarui',
            'prodi' => 'Sistem Informasi',
        ])->assertRedirect(route('dosen.index'));
    }

    public function test_dosen_index_searches_filters_sorts_and_paginates(): void
    {
        foreach (range(1, 11) as $index) {
            Dosen::create([
                'nidn' => sprintf('300000%04d', $index),
                'nama' => sprintf('Dosen %02d', $index),
                'prodi' => $index === 11 ? 'Sistem Informasi' : 'Teknik Informatika',
            ]);
        }

        $this->get(route('dosen.index', ['search' => 'Dosen 11', 'prodi' => 'Sistem Informasi']))
            ->assertOk()
            ->assertSee('Dosen 11')
            ->assertSee('Menampilkan 1–1 dari 1 dosen.');

        $this->get(route('dosen.index', ['sort' => 'nama', 'direction' => 'desc']))
            ->assertOk()
            ->assertSee('Menampilkan 1–10 dari 11 dosen.')
            ->assertSee('Menampilkan 1–10 dari 11 hasil.')
            ->assertSee('Sebelumnya')
            ->assertSee('Dosen 11');

        $this->get(route('dosen.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Menampilkan 11–11 dari 11 dosen.')
            ->assertSee('Dosen 11')
            ->assertDontSee('Dosen 01');

        $this->get(route('dosen.index', ['sort' => ['nama'], 'direction' => ['desc'], 'prodi' => ['Informatika']]))
            ->assertOk()
            ->assertSee('Menampilkan 1–10 dari 11 dosen.');
    }
}
