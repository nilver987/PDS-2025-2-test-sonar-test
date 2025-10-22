<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gerencia;
use App\Http\Controllers\AdminGerenciaController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminGerenciaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new AdminGerenciaController();
    }

    /** @test */
    public function it_validates_store_gerencia_request()
    {
        $request = new Request([
            'nombre' => '',
            'codigo' => '',
        ]);

        $response = $this->controller->store($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_validates_gerencia_tipo_subgerencia_requires_parent()
    {
        $request = new Request([
            'nombre' => 'Test Subgerencia',
            'codigo' => 'SUB-001',
            'tipo' => 'subgerencia',
        ]);

        $response = $this->controller->store($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_validates_update_gerencia_request()
    {
        $gerencia = Gerencia::factory()->create();
        $request = new Request(['nombre' => '']);
        
        $response = $this->controller->update($request, $gerencia);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_validates_unique_gerencia_codigo()
    {
        Gerencia::factory()->create(['codigo' => 'TEST-001']);
        
        $request = new Request([
            'nombre' => 'Test Gerencia',
            'codigo' => 'TEST-001',
            'tipo' => 'gerencia'
        ]);

        $response = $this->controller->store($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_can_get_gerencia_statistics()
    {
        Gerencia::factory()->count(3)->create(['tipo' => 'gerencia']);
        Gerencia::factory()->count(2)->create(['tipo' => 'subgerencia']);

        $response = $this->controller->estadisticas();
        $data = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(3, $data['total_gerencias']);
        $this->assertEquals(2, $data['total_subgerencias']);
    }
}
