<?php

namespace Tests\Feature\Api;

use App\Models\Petugas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_login_with_valid_credentials()
    {
        // Create a petugas with known credentials
        $petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'petugas' => [
                    'id',
                    'username'
                ]
            ])
            ->assertJson([
                'message' => 'Login successful',
                'petugas' => [
                    'id' => $petugas->id,
                    'username' => 'testuser'
                ]
            ]);

        $this->assertNotEmpty($response->json('token'));
    }

    /** @test */
    public function it_cannot_login_with_invalid_credentials()
    {
        // Create a petugas
        Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    /** @test */
    public function it_cannot_login_with_nonexistent_user()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'nonexistent',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    /** @test */
    public function it_requires_username_and_password_for_login()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);
    }

    /** @test */
    public function it_can_logout_authenticated_user()
    {
        // Create a petugas and login to get token
        $petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('token');

        // Use token to logout
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logout successful'
            ]);
    }

    /** @test */
    public function it_cannot_logout_unauthenticated_user()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_get_current_user_info()
    {
        // Create a petugas and login to get token
        $petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $token = $loginResponse->json('token');

        // Use token to get user info
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'petugas' => [
                    'id',
                    'username'
                ]
            ])
            ->assertJson([
                'petugas' => [
                    'id' => $petugas->id,
                    'username' => $petugas->username
                ]
            ]);
    }

    /** @test */
    public function it_cannot_get_user_info_when_unauthenticated()
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_revokes_old_tokens_on_new_login()
    {
        // Create a petugas
        $petugas = Petugas::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        // First login
        $response1 = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $token1 = $response1->json('token');

        // Second login
        $response2 = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $token2 = $response2->json('token');

        // First token should be invalid
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
            'Accept' => 'application/json'
        ])->getJson('/api/me')->assertStatus(401);

        // Second token should be valid
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
            'Accept' => 'application/json'
        ])->getJson('/api/me')->assertStatus(200);
    }

    /** @test */
    public function it_validates_username_format()
    {
        $response = $this->postJson('/api/login', [
            'username' => '',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    /** @test */
    public function it_validates_password_format()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
