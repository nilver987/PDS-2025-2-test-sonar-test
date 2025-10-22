<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Expediente;
use App\Models\User;
use App\Models\Gerencia;
use App\Models\TipoTramite;
use Carbon\Carbon;

class ExpedienteModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function expediente_factory_creates_record_with_relations()
    {
        $exp = Expediente::factory()->create();
        $this->assertDatabaseHas('expedientes', ['id' => $exp->id]);
        $this->assertNotNull($exp->tipoTramite);
    }

    /** @test */
    public function expediente_has_usuario_registro_id()
    {
        $exp = Expediente::factory()->create();
        $this->assertNotNull($exp->usuario_registro_id);
    }

    /** @test */
    public function expediente_default_estado_is_set()
    {
        $exp = Expediente::factory()->create();
        $this->assertNotEmpty($exp->estado);
    }

    /** @test */
    public function expediente_has_fecha_registro()
    {
        $exp = Expediente::factory()->create();
        $this->assertNotNull($exp->fecha_registro);
    }

    /** @test */
    public function expediente_belongs_to_gerencia()
    {
        $exp = Expediente::factory()->create();
        $this->assertNotNull($exp->gerencia_id);
    }

    /** @test */
    public function expediente_numero_format_matches_expected_pattern()
    {
        $exp = Expediente::factory()->create();
        $this->assertMatchesRegularExpression('/^EXP-\d{4}-\d{6}$/', $exp->numero);
    }

    /** @test */
    public function expediente_can_set_fecha_resolucion_and_compute_days()
    {
        $exp = Expediente::factory()->create(['fecha_registro' => Carbon::now()->subDays(5)]);
        $exp->fecha_resolucion = Carbon::now();
        $exp->save();
        $diff = $exp->fecha_registro->diffInDays($exp->fecha_resolucion);
        // Casting a int para evitar diferencias por microsegundos
        $this->assertEquals(5, (int) $diff);
    }

    /** @test */
    public function expediente_update_changes_estado()
    {
        $exp = Expediente::factory()->create(['estado' => 'pendiente']);
        $exp->update(['estado' => 'resuelto']);
        $this->assertEquals('resuelto', $exp->fresh()->estado);
    }

    /** @test */
    public function expediente_deleted_is_removed_from_db()
    {
        $exp = Expediente::factory()->create();
        $id = $exp->id;
        $exp->delete();

        // Si el modelo utiliza soft deletes, assertSoftDeleted; en caso contrario assertDatabaseMissing
        if (method_exists($exp, 'trashed') || in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses($exp))) {
            $this->assertSoftDeleted('expedientes', ['id' => $id]);
        } else {
            $this->assertDatabaseMissing('expedientes', ['id' => $id]);
        }
    }

    /** @test */
    public function expediente_tipo_tramite_relation_present()
    {
        $exp = Expediente::factory()->create();
        $this->assertNotNull($exp->tipoTramite);
        $this->assertNotNull($exp->tipo_tramite_id ?? $exp->tipoTramite->id);
    }
}
