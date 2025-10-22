<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Gerencia;
use App\Models\User;

class GerenciaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_subgerencias_and_users()
    {
        $parent = Gerencia::factory()->create(['tipo' => Gerencia::TIPO_GERENCIA]);
        $child = Gerencia::factory()->create(['tipo' => Gerencia::TIPO_SUBGERENCIA, 'gerencia_padre_id' => $parent->id]);
        $user = User::factory()->create(['gerencia_id' => $parent->id]);

        $this->assertTrue($parent->subgerencias->contains($child));
        $this->assertTrue($parent->users->contains($user));
    }

    /** @test */
    public function activas_scope_filters_only_active_gerencias()
    {
        Gerencia::factory()->create(['activo' => true, 'nombre' => 'Activa']);
        Gerencia::factory()->create(['activo' => false, 'nombre' => 'Inactiva']);

        $actives = Gerencia::activas()->get();

        $this->assertCount(1, $actives);
        $this->assertEquals('Activa', $actives->first()->nombre);
    }
}
