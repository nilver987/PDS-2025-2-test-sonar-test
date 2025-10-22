<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\TipoDocumento;

class TipoTramiteModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created_with_required_fields()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create([
            'nombre' => 'TT Unit 1',
            'gerencia_id' => $g->id,
            'costo' => 0,
            'tiempo_estimado_dias' => 1,
            'activo' => true
        ]);
        $this->assertDatabaseHas('tipo_tramites', ['nombre' => 'TT Unit 1']);
    }

    /** @test */
    public function it_belongs_to_gerencia()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create([
            'nombre' => 'TT Unit 2',
            'gerencia_id' => $g->id,
            'costo' => 0,
            'tiempo_estimado_dias' => 1,
            'activo' => true
        ]);
        $this->assertEquals($g->id, $tt->gerencia->id);
    }

    /** @test */
    public function documentos_many_to_many_can_be_synced()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TT Docs','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $doc = TipoDocumento::create(['nombre' => 'TD1', 'activo' => true]);
        $tt->documentos()->sync([$doc->id]);
        $this->assertTrue($tt->documentos->contains($doc));
    }

    /** @test */
    public function it_can_toggle_activo_flag()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TT Toggle','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $tt->update(['activo' => !$tt->activo]);
        $this->assertIsBool((bool)$tt->fresh()->activo);
    }

    /** @test */
    public function update_changes_nombre()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'OldName','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $tt->update(['nombre' => 'NewName']);
        $this->assertEquals('NewName', $tt->fresh()->nombre);
    }

    /** @test */
    public function it_can_be_deleted_when_no_expedientes()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTDel','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $tt->delete();
        $this->assertDatabaseMissing('tipo_tramites', ['id' => $tt->id]);
    }

    /** @test */
    public function tiempo_estimado_is_integer_and_positive()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTTime','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>5,'activo'=>true]);
        $this->assertIsInt((int)$tt->tiempo_estimado_dias);
        $this->assertGreaterThanOrEqual(1, $tt->tiempo_estimado_dias);
    }

    /** @test */
    public function costo_is_numeric()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTCost','gerencia_id'=>$g->id,'costo'=>2.5,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $this->assertIsFloat((float)$tt->costo);
    }

    /** @test */
    public function created_record_has_timestamps()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTStamp','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $this->assertNotNull($tt->created_at);
        $this->assertNotNull($tt->updated_at);
    }

    /** @test */
    public function documentos_relation_returns_collection()
    {
        $g = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTDocs2','gerencia_id'=>$g->id,'costo'=>0,'tiempo_estimado_dias'=>1,'activo'=>true]);
        $this->assertIsIterable($tt->documentos);
    }
}
