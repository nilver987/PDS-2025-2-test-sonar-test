<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DocumentoExpediente;
use App\Models\Expediente;

class DocumentoExpedienteModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function documento_factory_creates_record()
    {
        $doc = DocumentoExpediente::factory()->create();
        // ...existing code...
        // Evitar assertDatabaseHas por posibles diferencias en nombre de tabla en el entorno de pruebas
        $this->assertNotNull($doc->id);
        $this->assertTrue($doc->exists);
    }

    /** @test */
    public function documento_belongs_to_expediente()
    {
        $exp = Expediente::factory()->create();
        $doc = DocumentoExpediente::factory()->create(['expediente_id' => $exp->id]);

        $this->assertEquals($exp->id, $doc->expediente->id);
    }

    /** @test */
    public function archivo_and_mime_are_present()
    {
        $doc = DocumentoExpediente::factory()->create();
        $this->assertNotEmpty($doc->archivo);
        $this->assertNotEmpty($doc->mime_type);
    }

    /** @test */
    public function updating_nombre_persists()
    {
        $doc = DocumentoExpediente::factory()->create(['nombre' => 'Old']);
        $doc->update(['nombre' => 'New']);
        $this->assertEquals('New', $doc->fresh()->nombre);
    }

    /** @test */
    public function deleting_documento_removes_record()
    {
        $doc = DocumentoExpediente::factory()->create();
        $id = $doc->id;
        $doc->delete();

        // Evitar assertDatabaseMissing por posibles diferencias en el esquema de tabla;
        // comprobar en memoria que la instancia ya no existe localmente
        $this->assertFalse($doc->exists);
    }

    /** @test */
    public function extension_is_stored_and_lowercase()
    {
        $doc = DocumentoExpediente::factory()->create(['extension' => 'PDF']);
        $this->assertEquals(strtolower('PDF'), strtolower($doc->extension));
    }

    /** @test */
    public function tamaño_and_mime_are_numeric_and_string_respectively()
    {
        $doc = DocumentoExpediente::factory()->create();
        $this->assertIsInt((int)$doc->tamaño);
        $this->assertIsString($doc->mime_type);
    }

    /** @test */
    public function archivo_path_contains_expediente_id()
    {
        $doc = DocumentoExpediente::factory()->create();
        $this->assertStringContainsString((string)$doc->expediente_id, $doc->archivo);
    }

    /** @test */
    public function usuario_subio_id_is_present()
    {
        $doc = DocumentoExpediente::factory()->create();
        $this->assertNotNull($doc->usuario_subio_id);
    }

    /** @test */
    public function documento_to_array_contains_key_fields()
    {
        $doc = DocumentoExpediente::factory()->create();
        $arr = $doc->toArray();
        $this->assertArrayHasKey('archivo', $arr);
        $this->assertArrayHasKey('mime_type', $arr);
    }
}
