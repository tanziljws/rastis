<?php

namespace Tests\Feature\Api;

use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $token;
    private $petugas;
    private $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);
        
        $this->kategori = Kategori::factory()->create([
            'judul' => 'Test Category'
        ]);

        // Login to get token
        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $this->token = $loginResponse->json('token');
    }

    /** @test */
    public function guest_can_view_posts_list()
    {
        // Create some posts
        Post::factory()->count(3)->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
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
                    ]
                ],
                'links',
                'meta'
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function guest_can_view_single_post()
    {
        $post = Post::factory()->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
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
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'judul' => $post->judul,
                    'isi' => $post->isi,
                    'status' => $post->status
                ]
            ]);
    }

    /** @test */
    public function guest_cannot_create_post()
    {
        $response = $this->postJson('/api/posts', [
            'judul' => 'Test Post',
            'isi' => 'Test content',
            'status' => 'published'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_update_post()
    {
        $post = Post::factory()->create();

        $response = $this->putJson("/api/posts/{$post->id}", [
            'judul' => 'Updated Post',
            'isi' => 'Updated content',
            'status' => 'published'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_create_post()
    {
        $postData = [
            'judul' => 'Test Post',
            'isi' => 'Test content for the post',
            'kategori_id' => $this->kategori->id,
            'status' => 'published'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
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
                ]
            ])
            ->assertJson([
                'data' => [
                    'judul' => 'Test Post',
                    'isi' => 'Test content for the post',
                    'status' => 'published',
                    'petugas' => [
                        'id' => $this->petugas->id,
                        'username' => $this->petugas->username
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'judul' => 'Test Post',
            'isi' => 'Test content for the post',
            'status' => 'published',
            'petugas_id' => $this->petugas->id,
            'kategori_id' => $this->kategori->id
        ]);
    }

    /** @test */
    public function admin_can_update_post()
    {
        $post = Post::factory()->create([
            'petugas_id' => $this->petugas->id,
            'kategori_id' => $this->kategori->id
        ]);

        $updateData = [
            'judul' => 'Updated Post Title',
            'isi' => 'Updated content for the post',
            'status' => 'draft'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'judul' => 'Updated Post Title',
                    'isi' => 'Updated content for the post',
                    'status' => 'draft'
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'judul' => 'Updated Post Title',
            'isi' => 'Updated content for the post',
            'status' => 'draft'
        ]);
    }

    /** @test */
    public function admin_can_delete_post()
    {
        $post = Post::factory()->create(['petugas_id' => $this->petugas->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    public function it_validates_required_fields_for_post_creation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/posts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'isi', 'status']);
    }

    /** @test */
    public function it_validates_status_values()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/posts', [
            'judul' => 'Test Post',
            'isi' => 'Test content',
            'status' => 'invalid_status'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /** @test */
    public function it_validates_kategori_id_exists()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/posts', [
            'judul' => 'Test Post',
            'isi' => 'Test content',
            'status' => 'published',
            'kategori_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['kategori_id']);
    }

    /** @test */
    public function it_supports_search_functionality()
    {
        Post::factory()->create([
            'judul' => 'School Activities',
            'isi' => 'Content about school activities',
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        Post::factory()->create([
            'judul' => 'Other Topic',
            'isi' => 'Content about other topic',
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/posts?search=school');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('School Activities', $response->json('data.0.judul'));
    }

    /** @test */
    public function it_supports_filtering_by_kategori()
    {
        $kategori2 = Kategori::factory()->create(['judul' => 'Category 2']);
        
        Post::factory()->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        Post::factory()->create([
            'kategori_id' => $kategori2->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        $response = $this->getJson("/api/posts?kategori_id={$this->kategori->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($this->kategori->id, $response->json('data.0.kategori.id'));
    }

    /** @test */
    public function it_supports_filtering_by_status()
    {
        Post::factory()->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        Post::factory()->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'draft'
        ]);

        $response = $this->getJson('/api/posts?status=published');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('published', $response->json('data.0.status'));
    }

    /** @test */
    public function it_returns_404_for_nonexistent_post()
    {
        $response = $this->getJson('/api/posts/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_supports_pagination()
    {
        Post::factory()->count(25)->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id,
            'status' => 'published'
        ]);

        $response = $this->getJson('/api/posts?per_page=10');

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
    public function it_handles_partial_updates()
    {
        $post = Post::factory()->create([
            'petugas_id' => $this->petugas->id,
            'kategori_id' => $this->kategori->id,
            'judul' => 'Original Title',
            'isi' => 'Original content',
            'status' => 'published'
        ]);

        // Update only judul
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/posts/{$post->id}", [
            'judul' => 'Updated Title'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'judul' => 'Updated Title',
                    'isi' => 'Original content',
                    'status' => 'published'
                ]
            ]);

        // Update only isi
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/posts/{$post->id}", [
            'isi' => 'Updated content'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'judul' => 'Updated Title',
                    'isi' => 'Updated content',
                    'status' => 'published'
                ]
            ]);
    }
}
