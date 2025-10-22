<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TipoDocumento;

class TipoDocumentoModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_tipo_documento()
    {
        $td = TipoDocumento::create(['nombre' => 'TD Unit', 'activo' => true]);
        $this->assertDatabaseHas('tipo_documentos', ['nombre' => 'TD Unit']);
    }

    /** @test */
    public function activo_field_is_boolean()
    {
        $td = TipoDocumento::create(['nombre' => 'TD Bool', 'activo' => false]);
        $this->assertIsBool((bool)$td->activo);
    }

    /** @test */
    public function update_changes_nombre()
    {
        $td = TipoDocumento::create(['nombre' => 'OldTD', 'activo' => true]);
        $td->update(['nombre' => 'NewTD']);
        $this->assertEquals('NewTD', $td->fresh()->nombre);
    }

    /** @test */
    public function delete_removes_record()
    {
        $td = TipoDocumento::create(['nombre' => 'TDDel', 'activo' => true]);
        $id = $td->id;
        $td->delete();
        $this->assertDatabaseMissing('tipo_documentos', ['id' => $id]);
    }

    /** @test */
    public function timestamps_are_present()
    {
        $td = TipoDocumento::create(['nombre' => 'TDStamp', 'activo' => true]);
        $this->assertNotNull($td->created_at);
        $this->assertNotNull($td->updated_at);
    }

    /** @test */
    public function multiple_tipo_documentos_can_be_created()
    {
        TipoDocumento::create(['nombre' => 'TD1', 'activo' => true]);
        TipoDocumento::create(['nombre' => 'TD2', 'activo' => true]);
        $this->assertCount(2, TipoDocumento::all());
    }

    /** @test */
    public function to_array_contains_nombre()
    {
        $td = TipoDocumento::create(['nombre' => 'TDArray', 'activo' => true]);
        $this->assertArrayHasKey('nombre', $td->toArray());
    }

    /** @test */
    public function nombre_field_is_string()
    {
        $td = TipoDocumento::create(['nombre' => 'TDString', 'activo' => true]);
        $this->assertIsString($td->nombre);
    }

    /** @test */
    public function can_toggle_activo_flag()
    {
        $td = TipoDocumento::create(['nombre' => 'TDToggle', 'activo' => true]);
        $td->update(['activo' => !$td->activo]);
        $this->assertIsBool((bool)$td->fresh()->activo);
    }

    /** @test */
    public function search_by_nombre_returns_results()
    {
        TipoDocumento::create(['nombre' => 'TDSearch', 'activo' => true]);
        $found = TipoDocumento::where('nombre', 'like', '%TDSearch%')->first();
        $this->assertNotNull($found);
    }
}
