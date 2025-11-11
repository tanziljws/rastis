<?php

namespace Tests\Feature\Web;

use App\Models\Foto;
use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function homepage_displays_profile_and_gallery_grid()
    {
        Storage::fake('public');

        // Seed minimal data
        $profile = Profile::factory()->create([
            'judul' => 'Profil Sekolah SMK X',
            'isi' => 'Sejarah singkat sekolah.'
        ]);

        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id,
            'status' => 'published'
        ]);
        $galery = Galery::factory()->create(['post_id' => $post->id, 'status' => 'active']);

        // Create a few photos
        $f1 = Foto::factory()->create(['galery_id' => $galery->id, 'file' => 'foto/a.jpg', 'judul' => 'A']);
        $f2 = Foto::factory()->create(['galery_id' => $galery->id, 'file' => 'foto/b.jpg', 'judul' => 'B']);

        // Ensure storage files exist virtually
        Storage::disk('public')->put('foto/a.jpg', 'x');
        Storage::disk('public')->put('foto/b.jpg', 'y');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Profil Sekolah', false);
        $response->assertSee($profile->judul, false);
        // Ensure gallery grid anchors rendered with lightbox attributes
        $response->assertSee('data-lightbox="sgallery"', false);
        $response->assertSee('A', false);
        $response->assertSee('B', false);
    }
}




