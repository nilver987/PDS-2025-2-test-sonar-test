<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Expediente;
use App\Models\User;
use App\Models\TipoTramite;
use App\Models\Gerencia;

class ExpedienteFactory extends Factory
{
    protected $model = Expediente::class;

    public function definition()
    {
        $user = User::factory()->create();
        $gerencia = Gerencia::factory()->create();
        $tipo = TipoTramite::first() ?? TipoTramite::create([
            'nombre' => 'TipoTest',
            'gerencia_id' => $gerencia->id,
            'activo' => true,
            'costo' => 0,
            'tiempo_estimado_dias' => 1
        ]);

        return [
            'numero' => 'EXP-' . date('Y') . '-' . $this->faker->unique()->numerify('######'),
            'tipo_tramite_id' => $tipo->id,
            'workflow_id' => null,
            'current_step_id' => null,
            'solicitante_nombre' => $user->name,
            'solicitante_dni' => $user->dni ?? '00000000',
            'solicitante_telefono' => $user->telefono ?? '000000000',
            'solicitante_email' => $user->email,
            'tipo_tramite' => strtolower(str_replace(' ', '_', $tipo->nombre)),
            'asunto' => $this->faker->sentence(6),
            'descripcion' => $this->faker->paragraph(),
            'estado' => Expediente::ESTADO_PENDIENTE ?? 'pendiente',
            'gerencia_id' => $gerencia->id,
            'fecha_registro' => now(),
            'fecha_resolucion' => null,
            'usuario_registro_id' => $user->id,
        ];
    }
}
