<?php

namespace Tests\Feature\Api;

use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GaleryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        config(['sanctum.user_model' => Petugas::class]);
    }

    /** @test */
    public function guest_can_view_galeries_list()
    {
        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        
        Galery::factory()->count(3)->create(['post_id' => $post->id]);

        $response = $this->getJson('/api/galeries');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'position',
                        'status',
                        'post' => [
                            'id',
                            'judul',
                            'isi',
                            'status',
                            'kategori' => [
                                'id',
                                'judul'
                            ],
                            'petugas' => [
                                'id',
                                'username'
                            ],
                            'galeries_count',
                            'created_at',
                            'updated_at'
                        ],
                        'fotos_count',
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
    public function guest_can_view_single_galery()
    {
        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        $galery = Galery::factory()->create(['post_id' => $post->id]);

        $response = $this->getJson("/api/galeries/{$galery->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position',
                    'status',
                    'post' => [
                        'id',
                        'judul',
                        'isi',
                        'status',
                        'kategori' => [
                            'id',
                            'judul'
                        ],
                        'petugas' => [
                            'id',
                            'username'
                        ],
                        'galeries_count',
                        'created_at',
                        'updated_at'
                    ],
                    'fotos_count',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $galery->id,
                    'position' => $galery->position,
                    'status' => $galery->status
                ]
            ]);
    }

    /** @test */
    public function guest_cannot_create_galery()
    {
        $response = $this->postJson('/api/galeries', [
            'post_id' => 1,
            'position' => 1,
            'status' => 'active'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_update_galery()
    {
        $galery = Galery::factory()->create();

        $response = $this->putJson("/api/galeries/{$galery->id}", [
            'position' => 2,
            'status' => 'inactive'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_delete_galery()
    {
        $galery = Galery::factory()->create();

        $response = $this->deleteJson("/api/galeries/{$galery->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_create_galery()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        Sanctum::actingAs($petugas);

        $galeryData = [
            'post_id' => $post->id,
            'position' => 1,
            'status' => 'active'
        ];

        $response = $this->postJson('/api/galeries', $galeryData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'position',
                    'status',
                    'post' => [
                        'id',
                        'judul',
                        'isi',
                        'status',
                        'kategori' => [
                            'id',
                            'judul'
                        ],
                        'petugas' => [
                            'id',
                            'username'
                        ],
                        'galeries_count',
                        'created_at',
                        'updated_at'
                    ],
                    'fotos_count',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'position' => 1,
                    'status' => 'active',
                    'post' => [
                        'id' => $post->id
                    ]
                ]
            ]);

        $this->assertDatabaseHas('galery', [
            'post_id' => $post->id,
            'position' => 1,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function admin_can_update_galery()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        $galery = Galery::factory()->create(['post_id' => $post->id]);
        Sanctum::actingAs($petugas);

        $updateData = [
            'position' => 2,
            'status' => 'inactive'
        ];

        $response = $this->putJson("/api/galeries/{$galery->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $galery->id,
                    'position' => 2,
                    'status' => 'inactive'
                ]
            ]);

        $this->assertDatabaseHas('galery', [
            'id' => $galery->id,
            'position' => 2,
            'status' => 'inactive'
        ]);
    }

    /** @test */
    public function admin_can_delete_galery()
    {
        $petugas = Petugas::factory()->create();
        $kategori = Kategori::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        $galery = Galery::factory()->create(['post_id' => $post->id]);
        Sanctum::actingAs($petugas);

        $response = $this->deleteJson("/api/galeries/{$galery->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('galery', ['id' => $galery->id]);
    }

    /** @test */
    public function it_validates_required_fields_for_galery_creation()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/galeries', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['post_id', 'position', 'status']);
    }

    /** @test */
    public function it_validates_post_id_exists()
    {
        $petugas = Petugas::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/galeries', [
            'post_id' => 999,
            'position' => 1,
            'status' => 'active'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['post_id']);
    }

    /** @test */
    public function it_validates_status_values()
    {
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/galeries', [
            'post_id' => $post->id,
            'position' => 1,
            'status' => 'invalid_status'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /** @test */
    public function it_validates_position_is_integer()
    {
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/galeries', [
            'post_id' => $post->id,
            'position' => 'not_a_number',
            'status' => 'active'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['position']);
    }

    /** @test */
    public function it_supports_filtering_by_post()
    {
        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post1 = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        $post2 = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        
        Galery::factory()->create(['post_id' => $post1->id]);
        Galery::factory()->create(['post_id' => $post2->id]);

        $response = $this->getJson("/api/galeries?post_id={$post1->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($post1->id, $response->json('data.0.post.id'));
    }

    /** @test */
    public function it_supports_filtering_by_status()
    {
        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        
        Galery::factory()->create([
            'post_id' => $post->id,
            'status' => 'active'
        ]);

        Galery::factory()->create([
            'post_id' => $post->id,
            'status' => 'inactive'
        ]);

        $response = $this->getJson('/api/galeries?status=active');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('active', $response->json('data.0.status'));
    }

    /** @test */
    public function it_returns_404_for_nonexistent_galery()
    {
        $response = $this->getJson('/api/galeries/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_supports_pagination()
    {
        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        
        Galery::factory()->count(25)->create(['post_id' => $post->id]);

        $response = $this->getJson('/api/galeries?per_page=10');

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
    public function it_includes_fotos_count()
    {
        $kategori = Kategori::factory()->create();
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create([
            'kategori_id' => $kategori->id,
            'petugas_id' => $petugas->id
        ]);
        $galery = Galery::factory()->create(['post_id' => $post->id]);

        // Create some fotos for this galery
        $galery->fotos()->createMany([
            ['file' => 'foto1.jpg', 'judul' => 'Foto 1'],
            ['file' => 'foto2.jpg', 'judul' => 'Foto 2'],
            ['file' => 'foto3.jpg', 'judul' => 'Foto 3'],
        ]);

        $response = $this->getJson("/api/galeries/{$galery->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $galery->id,
                    'fotos_count' => 3
                ]
            ]);
    }

    /** @test */
    public function it_handles_partial_updates()
    {
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create();
        $galery = Galery::factory()->create([
            'post_id' => $post->id,
            'position' => 1,
            'status' => 'active'
        ]);
        Sanctum::actingAs($petugas);

        // Update only position
        $response = $this->putJson("/api/galeries/{$galery->id}", [
            'position' => 2
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $galery->id,
                    'position' => 2,
                    'status' => 'active'
                ]
            ]);

        // Update only status
        $response = $this->putJson("/api/galeries/{$galery->id}", [
            'status' => 'inactive'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $galery->id,
                    'position' => 2,
                    'status' => 'inactive'
                ]
            ]);
    }

    /** @test */
    public function it_handles_negative_position()
    {
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/galeries', [
            'post_id' => $post->id,
            'position' => -1,
            'status' => 'active'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['position']);
    }

    /** @test */
    public function it_handles_zero_position()
    {
        $petugas = Petugas::factory()->create();
        $post = Post::factory()->create();
        Sanctum::actingAs($petugas);

        $response = $this->postJson('/api/galeries', [
            'post_id' => $post->id,
            'position' => 0,
            'status' => 'active'
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_returns_proper_json_structure_for_empty_list()
    {
        $response = $this->getJson('/api/galeries');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);

        $this->assertCount(0, $response->json('data'));
        $this->assertEquals(0, $response->json('meta.total'));
    }
}
