<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\TipoDocumento;

class TipoTramiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tipo_tramite_belongs_to_gerencia()
    {
        $gerencia = Gerencia::factory()->create();
        $tt = TipoTramite::create([
            'nombre' => 'TT Test',
            'gerencia_id' => $gerencia->id,
            'costo' => 0,
            'tiempo_estimado_dias' => 1,
            'activo' => true
        ]);

        $this->assertNotNull($tt->gerencia);
        $this->assertEquals($gerencia->id, $tt->gerencia->id);
    }

    /** @test */
    public function documentos_relation_can_be_synced()
    {
        $gerencia = Gerencia::factory()->create();
        $tt = TipoTramite::create([
            'nombre' => 'TT Docs',
            'gerencia_id' => $gerencia->id,
            'costo' => 0,
            'tiempo_estimado_dias' => 1,
            'activo' => true
        ]);

        $doc = TipoDocumento::create(['nombre' => 'Doc Test', 'activo' => true]);

        $tt->documentos()->sync([$doc->id]);

        $this->assertTrue($tt->documentos->contains($doc));
    }
}
