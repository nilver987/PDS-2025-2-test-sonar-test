<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Expediente;

class ExpedienteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function expediente_factory_creates_tipo_tramite_and_user()
    {
        $exp = Expediente::factory()->create();

        $this->assertNotNull($exp->tipoTramite);
        $this->assertNotNull($exp->usuario_registro_id);
    }

    /** @test */
    public function expediente_has_default_estado_pendiente()
    {
        $exp = Expediente::factory()->create();

        $expected = defined('App\Models\Expediente::ESTADO_PENDIENTE') ? Expediente::ESTADO_PENDIENTE : 'pendiente';
        $this->assertEquals($expected, $exp->estado);
    }
}
