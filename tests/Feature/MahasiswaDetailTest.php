<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_page_displays_the_selected_mahasiswa(): void
    {
        $mahasiswa = Mahasiswa::factory()->create([
            'nim' => '240001',
            'nama' => 'Andi',
            'prodi' => 'Informatika',
            'email' => 'andi@example.com',
            'nohp' => '081234567890',
        ]);

        $response = $this->get("/detilmahasiswa/{$mahasiswa->id}");

        $response->assertOk()
            ->assertSee('240001')
            ->assertSee('Andi')
            ->assertSee('Informatika')
            ->assertSee('andi@example.com')
            ->assertSee('081234567890');
    }

    public function test_detail_page_returns_404_for_a_missing_mahasiswa(): void
    {
        $this->get('/detilmahasiswa/999999')->assertNotFound();
    }
}
