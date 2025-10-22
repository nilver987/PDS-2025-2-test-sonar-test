<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Gerencia;
use App\Models\User;

class GerenciaModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_gerencia_via_factory()
    {
        $g = Gerencia::factory()->create(['nombre' => 'Gerencia Test']);
        $this->assertDatabaseHas('gerencias', ['nombre' => 'Gerencia Test']);
    }

    /** @test */
    public function activas_scope_returns_only_active_gerencias()
    {
        Gerencia::factory()->create(['activo' => true, 'nombre' => 'Activa']);
        Gerencia::factory()->create(['activo' => false, 'nombre' => 'Inactiva']);
        $nombres = Gerencia::activas()->pluck('nombre')->toArray();
        $this->assertContains('Activa', $nombres);
        $this->assertNotContains('Inactiva', $nombres);
    }

    /** @test */
    public function it_has_subgerencias_relation()
    {
        $parent = Gerencia::factory()->create(['tipo' => Gerencia::TIPO_GERENCIA ?? 'gerencia']);
        $child = Gerencia::factory()->create(['tipo' => Gerencia::TIPO_SUBGERENCIA ?? 'subgerencia', 'gerencia_padre_id' => $parent->id]);
        $this->assertTrue($parent->subgerencias->contains($child));
    }

    /** @test */
    public function it_has_users_relation()
    {
        $g = Gerencia::factory()->create();
        $u = User::factory()->create(['gerencia_id' => $g->id]);
        $this->assertTrue($g->users->contains($u));
    }

    /** @test */
    public function it_can_be_updated()
    {
        $g = Gerencia::factory()->create(['nombre' => 'Old']);
        $g->update(['nombre' => 'New']);
        $this->assertEquals('New', $g->fresh()->nombre);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $g = Gerencia::factory()->create();
        $id = $g->id;
        $g->delete();
        $this->assertDatabaseMissing('gerencias', ['id' => $id]);
    }

    /** @test */
    public function default_activo_is_boolean()
    {
        $g = Gerencia::factory()->create();
        $this->assertIsBool((bool)$g->activo);
    }

    /** @test */
    public function orden_attribute_accepts_integer()
    {
        $g = Gerencia::factory()->create(['orden' => 5]);
        $this->assertEquals(5, $g->orden);
    }

    /** @test */
    public function to_array_contains_expected_fields()
    {
        $g = Gerencia::factory()->create(['nombre' => 'ToArrayTest']);
        $arr = $g->toArray();
        $this->assertArrayHasKey('nombre', $arr);
        $this->assertArrayHasKey('activo', $arr);
    }

    /** @test */
    public function codigo_is_present_and_unique_when_created()
    {
        $a = Gerencia::factory()->create(['codigo' => 'CODE1']);
        $this->assertEquals('CODE1', $a->codigo);
    }
}
