<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AuthControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data' => ['token']]);
    }

    /** @test */
    public function it_can_register_new_user()
    {
        Role::create(['name' => 'ciudadano']);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'ciudadano'
        ]);

        $response->assertStatus(201)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_get_authenticated_user()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                        ->getJson('/api/auth/user');

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
    }

    /** @test */
    public function it_can_refresh_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data' => ['token']]);
    }
}
