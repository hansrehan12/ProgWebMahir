<?php

namespace Tests\Feature;

use App\Models\Pengawas;
use Database\Seeders\PengawasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengawas_can_be_created_viewed_updated_and_deleted(): void
    {
        $this->post(route('pengawas.store'), [
            'nama' => 'Pengawas Baru',
            'alamat' => 'Jalan Merdeka 10',
            'notelp' => '081234567890',
            'email' => 'pengawas@example.com',
        ])->assertRedirect(route('pengawas.index'))
            ->assertSessionHas('success');

        $pengawas = Pengawas::where('email', 'pengawas@example.com')->firstOrFail();
        $this->assertDatabaseHas('pengawas', [
            'id' => $pengawas->id,
            'nama' => 'Pengawas Baru',
            'alamat' => 'Jalan Merdeka 10',
            'notelp' => '081234567890',
        ]);

        $this->get(route('pengawas.show', $pengawas))
            ->assertOk()
            ->assertSee('Pengawas Baru')
            ->assertSee('Jalan Merdeka 10')
            ->assertSee('081234567890');
        $this->get(route('pengawas.edit', $pengawas))
            ->assertOk()
            ->assertSee('value="pengawas@example.com"', false);

        $this->put(route('pengawas.update', $pengawas), [
            'nama' => 'Pengawas Diperbarui',
            'alamat' => 'Jalan Pendidikan 25',
            'notelp' => '081298765432',
            'email' => 'pengawas@example.com',
        ])->assertRedirect(route('pengawas.index'));
        $this->assertDatabaseHas('pengawas', [
            'id' => $pengawas->id,
            'nama' => 'Pengawas Diperbarui',
            'email' => 'pengawas@example.com',
        ]);

        $this->delete(route('pengawas.destroy', $pengawas))
            ->assertRedirect(route('pengawas.index'));
        $this->assertDatabaseMissing('pengawas', ['id' => $pengawas->id]);
    }

    public function test_pengawas_validation_rejects_duplicate_email_and_preserves_old_input(): void
    {
        Pengawas::create([
            'nama' => 'Pengawas Terdaftar',
            'alamat' => 'Jalan Lama 1',
            'notelp' => '081111111111',
            'email' => 'terdaftar@example.com',
        ]);

        $this->from(route('pengawas.create'))
            ->post(route('pengawas.store'), [
                'nama' => 'Nama Sementara',
                'alamat' => '',
                'notelp' => '0812345678901234567890',
                'email' => 'terdaftar@example.com',
            ])
            ->assertRedirect(route('pengawas.create'))
            ->assertSessionHasErrors(['alamat', 'notelp', 'email']);

        $this->get(route('pengawas.create'))
            ->assertOk()
            ->assertSee('value="Nama Sementara"', false)
            ->assertSee('Alamat wajib diisi.')
            ->assertSee('Email sudah terdaftar.');
    }

    public function test_pengawas_index_searches_sorts_and_paginates(): void
    {
        foreach (range(1, 11) as $index) {
            Pengawas::create([
                'nama' => sprintf('Pengawas %02d', $index),
                'alamat' => $index === 1 ? 'Alamat Khusus Cempaka' : sprintf('Jalan Nomor %d', $index),
                'notelp' => $index === 2 ? '089999991111' : sprintf('0812345%05d', $index),
                'email' => $index === 3 ? 'khusus@example.org' : sprintf('pengawas%02d@example.org', $index),
            ]);
        }

        $this->get(route('pengawas.index', ['search' => 'Cempaka']))
            ->assertOk()
            ->assertSee('Pengawas 01')
            ->assertSee('Menampilkan 1–1 dari 1 pengawas.');
        $this->get(route('pengawas.index', ['search' => 'Pengawas 05']))
            ->assertOk()
            ->assertSee('pengawas05@example.org');
        $this->get(route('pengawas.index', ['search' => '089999991111']))
            ->assertOk()
            ->assertSee('Pengawas 02');
        $this->get(route('pengawas.index', ['search' => 'khusus@example.org']))
            ->assertOk()
            ->assertSee('Pengawas 03');

        $this->get(route('pengawas.index', ['sort' => 'nama', 'direction' => 'desc']))
            ->assertOk()
            ->assertSee('Pengawas 11')
            ->assertSee('Menampilkan 1–10 dari 11 pengawas.');

        $this->get(route('pengawas.index', ['page' => 2, 'sort' => 'email', 'direction' => 'asc']))
            ->assertOk()
            ->assertSee('Pengawas 11')
            ->assertDontSee('Pengawas 01');

        $this->get(route('pengawas.index', ['sort' => ['nama'], 'direction' => ['desc']]))
            ->assertOk();
    }

    public function test_pengawas_seeder_is_idempotent(): void
    {
        $this->seed(PengawasSeeder::class);
        $this->seed(PengawasSeeder::class);

        $this->assertDatabaseCount('pengawas', 10);
    }
}
