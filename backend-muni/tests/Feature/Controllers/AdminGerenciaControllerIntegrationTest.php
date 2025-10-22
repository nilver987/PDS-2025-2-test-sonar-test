<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AdminGerenciaControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        
        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    /** @test */
    public function it_can_list_all_gerencias()
    {
        Gerencia::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/gerencias');
        
        $response->assertStatus(200)
                ->assertJsonStructure(['success', 'data']);
    }

    /** @test */
    public function it_can_create_gerencia()
    {
        $gerenciaData = [
            'nombre' => 'Nueva Gerencia',
            'codigo' => 'GER-001',
            'tipo' => 'gerencia'
        ];

        $response = $this->actingAs($this->admin)
                        ->postJson('/api/gerencias', $gerenciaData);

        $response->assertStatus(201)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_update_gerencia()
    {
        $gerencia = Gerencia::factory()->create();
        
        $updateData = [
            'nombre' => 'Gerencia Actualizada',
            'codigo' => 'GER-002'
        ];

        $response = $this->actingAs($this->admin)
                        ->putJson("/api/gerencias/{$gerencia->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_show_gerencia_details()
    {
        $gerencia = Gerencia::factory()->create();

        $response = $this->actingAs($this->admin)
                        ->getJson("/api/gerencias/{$gerencia->id}");

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_cannot_delete_gerencia_with_users()
    {
        $gerencia = Gerencia::factory()->create();
        User::factory()->create(['gerencia_id' => $gerencia->id]);

        $response = $this->actingAs($this->admin)
                        ->deleteJson("/api/gerencias/{$gerencia->id}");

        $response->assertStatus(400)
                ->assertJson(['success' => false]);
    }
}
