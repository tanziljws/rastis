<?php

namespace Tests\Feature\Api;

use App\Models\Foto;
use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FotoControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $token;
    private $petugas;
    private $kategori;
    private $post;
    private $galery;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        
        // Create test data
        $this->petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);
        
        $this->kategori = Kategori::factory()->create([
            'judul' => 'Test Category'
        ]);

        $this->post = Post::factory()->create([
            'kategori_id' => $this->kategori->id,
            'petugas_id' => $this->petugas->id
        ]);

        $this->galery = Galery::factory()->create([
            'post_id' => $this->post->id
        ]);

        // Login to get token
        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $this->token = $loginResponse->json('token');
    }

    /** @test */
    public function guest_can_view_fotos_list()
    {
        Foto::factory()->count(3)->create(['galery_id' => $this->galery->id]);

        $response = $this->getJson('/api/fotos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'file',
                        'file_url',
                        'judul',
                        'galery' => [
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
                        ],
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
    public function guest_can_view_single_foto()
    {
        $foto = Foto::factory()->create(['galery_id' => $this->galery->id]);

        $response = $this->getJson("/api/fotos/{$foto->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'file',
                    'file_url',
                    'judul',
                    'galery' => [
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
                    ],
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $foto->id,
                    'file' => $foto->file,
                    'judul' => $foto->judul
                ]
            ]);
    }

    /** @test */
    public function guest_cannot_upload_foto()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->postJson('/api/fotos', [
            'file' => $file,
            'judul' => 'Test Photo'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_update_foto()
    {
        $foto = Foto::factory()->create();

        $response = $this->putJson("/api/fotos/{$foto->id}", [
            'judul' => 'Updated Photo'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_delete_foto()
    {
        $foto = Foto::factory()->create();

        $response = $this->deleteJson("/api/fotos/{$foto->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_upload_foto()
    {
        $file = UploadedFile::fake()->image('test_photo.jpg', 800, 600);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file,
            'judul' => 'Test Photo',
            'galery_id' => $this->galery->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'file',
                    'file_url',
                    'judul',
                    'galery' => [
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
                    ],
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'judul' => 'Test Photo',
                    'galery' => [
                        'id' => $this->galery->id
                    ]
                ]
            ]);

        // Check if file was stored
        $foto = Foto::find($response->json('data.id'));
        $this->assertNotNull($foto);
        $this->assertNotEmpty($foto->file);
        Storage::disk('public')->assertExists($foto->file);
    }

    /** @test */
    public function admin_can_upload_foto_without_judul()
    {
        $file = UploadedFile::fake()->image('test_photo.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file,
            'galery_id' => $this->galery->id
        ]);

        $response->assertStatus(201);
        $this->assertNull($response->json('data.judul'));
    }

    /** @test */
    public function admin_can_update_foto()
    {
        $foto = Foto::factory()->create(['galery_id' => $this->galery->id]);

        $updateData = [
            'judul' => 'Updated Photo Title'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/fotos/{$foto->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $foto->id,
                    'judul' => 'Updated Photo Title'
                ]
            ]);

        $this->assertDatabaseHas('fotos', [
            'id' => $foto->id,
            'judul' => 'Updated Photo Title'
        ]);
    }

    /** @test */
    public function admin_can_delete_foto_and_file()
    {
        $foto = Foto::factory()->create(['galery_id' => $this->galery->id]);

        // Store a fake file
        $filePath = 'foto/test_file.jpg';
        Storage::disk('public')->put($filePath, 'fake file content');
        $foto->update(['file' => $filePath]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->deleteJson("/api/fotos/{$foto->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('fotos', ['id' => $foto->id]);
        Storage::disk('public')->assertMissing($filePath);
    }

    /** @test */
    public function it_validates_required_fields_for_foto_upload()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file', 'galery_id']);
    }

    /** @test */
    public function it_validates_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file,
            'galery_id' => $this->galery->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_validates_file_size()
    {
        $file = UploadedFile::fake()->image('large.jpg')->size(6000); // 6MB

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file,
            'galery_id' => $this->galery->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_validates_galery_id_exists()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file,
            'galery_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['galery_id']);
    }

    /** @test */
    public function it_supports_filtering_by_galery()
    {
        $galery2 = Galery::factory()->create(['post_id' => $this->post->id]);
        
        Foto::factory()->create(['galery_id' => $this->galery->id]);
        Foto::factory()->create(['galery_id' => $galery2->id]);

        $response = $this->getJson("/api/fotos?galery_id={$this->galery->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($this->galery->id, $response->json('data.0.galery.id'));
    }

    /** @test */
    public function it_supports_search_functionality()
    {
        Foto::factory()->create([
            'galery_id' => $this->galery->id,
            'judul' => 'School Event Photo'
        ]);

        Foto::factory()->create([
            'galery_id' => $this->galery->id,
            'judul' => 'Other Photo'
        ]);

        $response = $this->getJson('/api/fotos?search=school');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('School Event Photo', $response->json('data.0.judul'));
    }

    /** @test */
    public function it_returns_404_for_nonexistent_foto()
    {
        $response = $this->getJson('/api/fotos/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_supports_pagination()
    {
        Foto::factory()->count(25)->create(['galery_id' => $this->galery->id]);

        $response = $this->getJson('/api/fotos?per_page=10');

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
    public function it_generates_unique_filenames()
    {
        $file1 = UploadedFile::fake()->image('same_name.jpg');
        $file2 = UploadedFile::fake()->image('same_name.jpg');

        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file1,
            'galery_id' => $this->galery->id
        ]);

        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/fotos', [
            'file' => $file2,
            'galery_id' => $this->galery->id
        ]);

        $response1->assertStatus(201);
        $response2->assertStatus(201);

        $filename1 = $response1->json('data.file');
        $filename2 = $response2->json('data.file');

        $this->assertNotEquals($filename1, $filename2);
    }

    /** @test */
    public function it_handles_file_upload_with_different_image_formats()
    {
        $formats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($formats as $format) {
            $file = UploadedFile::fake()->image("test.{$format}");

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json'
            ])->postJson('/api/fotos', [
                'file' => $file,
                'galery_id' => $this->galery->id
            ]);

            $response->assertStatus(201);
        }
    }

    /** @test */
    public function it_handles_partial_updates()
    {
        $foto = Foto::factory()->create([
            'galery_id' => $this->galery->id,
            'judul' => 'Original Title'
        ]);

        // Update only judul
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/fotos/{$foto->id}", [
            'judul' => 'Updated Title'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $foto->id,
                    'judul' => 'Updated Title'
                ]
            ]);
    }
}
