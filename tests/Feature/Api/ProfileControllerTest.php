<?php

namespace Tests\Feature\Api;

use App\Models\Petugas;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $token;
    private $petugas;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        // Login to get token
        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $this->token = $loginResponse->json('token');
    }

    /** @test */
    public function guest_can_view_profiles_list()
    {
        Profile::factory()->count(3)->create();

        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'judul',
                        'isi',
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
    public function guest_can_view_single_profile()
    {
        $profile = Profile::factory()->create();

        $response = $this->getJson("/api/profiles/{$profile->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'judul',
                    'isi',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $profile->id,
                    'judul' => $profile->judul,
                    'isi' => $profile->isi
                ]
            ]);
    }

    /** @test */
    public function guest_cannot_create_profile()
    {
        $response = $this->postJson('/api/profiles', [
            'judul' => 'Test Profile',
            'isi' => 'Test content'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_update_profile()
    {
        $profile = Profile::factory()->create();

        $response = $this->putJson("/api/profiles/{$profile->id}", [
            'judul' => 'Updated Profile',
            'isi' => 'Updated content'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_delete_profile()
    {
        $profile = Profile::factory()->create();

        $response = $this->deleteJson("/api/profiles/{$profile->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_create_profile()
    {
        $profileData = [
            'judul' => 'School Profile',
            'isi' => 'This is the school profile content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', $profileData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'judul',
                    'isi',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'judul' => 'School Profile',
                    'isi' => 'This is the school profile content'
                ]
            ]);

        $this->assertDatabaseHas('profiles', [
            'judul' => 'School Profile',
            'isi' => 'This is the school profile content'
        ]);
    }

    /** @test */
    public function admin_can_update_profile()
    {
        $profile = Profile::factory()->create();

        $updateData = [
            'judul' => 'Updated School Profile',
            'isi' => 'Updated school profile content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/profiles/{$profile->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $profile->id,
                    'judul' => 'Updated School Profile',
                    'isi' => 'Updated school profile content'
                ]
            ]);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'judul' => 'Updated School Profile',
            'isi' => 'Updated school profile content'
        ]);
    }

    /** @test */
    public function admin_can_delete_profile()
    {
        $profile = Profile::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->deleteJson("/api/profiles/{$profile->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
    }

    /** @test */
    public function it_validates_required_fields_for_profile_creation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'isi']);
    }

    /** @test */
    public function it_validates_judul_max_length()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => str_repeat('a', 256), // Exceeds 255 characters
            'isi' => 'Valid content'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    /** @test */
    public function it_validates_isi_max_length()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => 'Valid Title',
            'isi' => str_repeat('a', 10001) // Exceeds 10000 characters
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['isi']);
    }

    /** @test */
    public function it_supports_search_functionality()
    {
        Profile::factory()->create([
            'judul' => 'School History',
            'isi' => 'Content about school history'
        ]);

        Profile::factory()->create([
            'judul' => 'Other Profile',
            'isi' => 'Content about other things'
        ]);

        $response = $this->getJson('/api/profiles?search=history');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('School History', $response->json('data.0.judul'));
    }

    /** @test */
    public function it_returns_404_for_nonexistent_profile()
    {
        $response = $this->getJson('/api/profiles/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_supports_pagination()
    {
        Profile::factory()->count(25)->create();

        $response = $this->getJson('/api/profiles?per_page=10');

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
        $profile = Profile::factory()->create([
            'judul' => 'Original Title',
            'isi' => 'Original content'
        ]);

        // Update only judul
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/profiles/{$profile->id}", [
            'judul' => 'Updated Title'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $profile->id,
                    'judul' => 'Updated Title',
                    'isi' => 'Original content'
                ]
            ]);

        // Update only isi
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/profiles/{$profile->id}", [
            'isi' => 'Updated content'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $profile->id,
                    'judul' => 'Updated Title',
                    'isi' => 'Updated content'
                ]
            ]);
    }

    /** @test */
    public function it_handles_empty_strings_in_validation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => '',
            'isi' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'isi']);
    }

    /** @test */
    public function it_handles_whitespace_only_strings()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => '   ',
            'isi' => '   '
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'isi']);
    }

    /** @test */
    public function it_returns_proper_json_structure_for_empty_list()
    {
        $response = $this->getJson('/api/profiles');

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
    public function it_handles_special_characters_in_content()
    {
        $specialContent = "Content with special chars: !@#$%^&*()_+-=[]{}|;':\",./<>?`~";
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => 'Special Characters Test',
            'isi' => $specialContent
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'judul' => 'Special Characters Test',
                    'isi' => $specialContent
                ]
            ]);
    }

    /** @test */
    public function it_handles_unicode_characters()
    {
        $unicodeContent = "Content with unicode: 中文, 日本語, 한국어, العربية, हिन्दी";
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => 'Unicode Test',
            'isi' => $unicodeContent
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'judul' => 'Unicode Test',
                    'isi' => $unicodeContent
                ]
            ]);
    }

    /** @test */
    public function it_handles_long_content()
    {
        $longContent = str_repeat('This is a long content. ', 100); // ~2500 characters
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/profiles', [
            'judul' => 'Long Content Test',
            'isi' => $longContent
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'judul' => 'Long Content Test',
                    'isi' => $longContent
                ]
            ]);
    }
}
