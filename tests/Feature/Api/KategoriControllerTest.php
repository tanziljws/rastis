<?php

namespace Tests\Feature\Api;

use App\Models\Kategori;
use App\Models\Petugas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KategoriControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        config(['sanctum.user_model' => Petugas::class]);
    }

    /** @test */
    public function admin_can_view_kategori_list()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        Kategori::factory()->count(3)->create();

        $response = $this->getJson('/api/kategori');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'judul',
                        'posts_count',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links',
                'meta'
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function admin_can_view_single_kategori()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->getJson("/api/kategori/{$kategori->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'judul',
                    'posts_count',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $kategori->id,
                    'judul' => $kategori->judul
                ]
            ]);
    }

    /** @test */
    public function admin_can_create_kategori()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $kategoriData = [
            'judul' => 'New Category'
        ];

        $response = $this->postJson('/api/kategori', $kategoriData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'judul',
                    'posts_count',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'judul' => 'New Category'
                ]
            ]);

        $this->assertDatabaseHas('kategori', [
            'judul' => 'New Category'
        ]);
    }

    /** @test */
    public function admin_can_update_kategori()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        Sanctum::actingAs($petugas);

        $updateData = [
            'judul' => 'Updated Category'
        ];

        $response = $this->putJson("/api/kategori/{$kategori->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $kategori->id,
                    'judul' => 'Updated Category'
                ]
            ]);

        $this->assertDatabaseHas('kategori', [
            'id' => $kategori->id,
            'judul' => 'Updated Category'
        ]);
    }

    /** @test */
    public function admin_can_delete_kategori()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->deleteJson("/api/kategori/{$kategori->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('kategori', ['id' => $kategori->id]);
    }

    /** @test */
    public function guest_cannot_access_kategori_endpoints()
    {
        $kategori = Kategori::factory()->create();

        // Test list
        $response = $this->getJson('/api/kategori');
        $response->assertStatus(401);

        // Test show
        $response = $this->getJson("/api/kategori/{$kategori->id}");
        $response->assertStatus(401);

        // Test create
        $response = $this->postJson('/api/kategori', ['judul' => 'Test']);
        $response->assertStatus(401);

        // Test update
        $response = $this->putJson("/api/kategori/{$kategori->id}", ['judul' => 'Updated']);
        $response->assertStatus(401);

        // Test delete
        $response = $this->deleteJson("/api/kategori/{$kategori->id}");
        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_required_fields_for_kategori_creation()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/kategori', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_validates_judul_max_length()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/kategori', [
            'judul' => str_repeat('a', 256) // Exceeds 255 characters
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_validates_judul_uniqueness()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        Kategori::factory()->create(['judul' => 'Existing Category']);

        $response = $this->postJson('/api/kategori', [
            'judul' => 'Existing Category'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_validates_judul_uniqueness_on_update()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        Kategori::factory()->create(['judul' => 'Existing Category']);
        $kategori = Kategori::factory()->create(['judul' => 'Original Category']);

        $response = $this->putJson("/api/kategori/{$kategori->id}", [
            'judul' => 'Existing Category'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_allows_same_judul_on_self_update()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $kategori = Kategori::factory()->create(['judul' => 'Original Category']);

        $response = $this->putJson("/api/kategori/{$kategori->id}", [
            'judul' => 'Original Category'
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_kategori()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->getJson('/api/kategori/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_supports_pagination()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        Kategori::factory()->count(25)->create();

        $response = $this->getJson('/api/kategori?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta' => [
                    'current_page',
                    'per_page',
                    'total'
                ]
            ]);

        $this->assertCount(10, $response->json('data'));
        $this->assertEquals(10, $response->json('meta.per_page'));
        $this->assertEquals(25, $response->json('meta.total'));
    }

    /** @test */
    public function it_includes_posts_count()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        Sanctum::actingAs($petugas);

        // Create some posts for this kategori
        $kategori->posts()->createMany([
            ['judul' => 'Post 1', 'isi' => 'Content 1', 'petugas_id' => $petugas->id, 'status' => 'published'],
            ['judul' => 'Post 2', 'isi' => 'Content 2', 'petugas_id' => $petugas->id, 'status' => 'published'],
        ]);

        $response = $this->getJson("/api/kategori/{$kategori->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $kategori->id,
                    'posts_count' => 2
                ]
            ]);
    }

    /** @test */
    public function it_handles_empty_strings_in_validation()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/kategori', [
            'judul' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_handles_whitespace_only_strings()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/kategori', [
            'judul' => '   '
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_returns_proper_json_structure_for_empty_list()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->getJson('/api/kategori');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);

        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('meta.total'));
    }

    /** @test */
    public function it_handles_special_characters_in_judul()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $specialJudul = "Category with special chars: !@#$%^&*()_+-=[]{}|;':\",./<>?`~";
        
        $response = $this->postJson('/api/kategori', [
            'judul' => $specialJudul
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'judul' => $specialJudul
                ]
            ]);
    }

    /** @test */
    public function it_handles_unicode_characters()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $unicodeJudul = "Kategori dengan karakter unicode: 中文, 日本語, 한국어, العربية, हिन्दी";
        
        $response = $this->postJson('/api/kategori', [
            'judul' => $unicodeJudul
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'judul' => $unicodeJudul
                ]
            ]);
    }
}
