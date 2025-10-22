<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gerencia;

class GerenciaFactory extends Factory
{
    protected $model = Gerencia::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->company(),
            'codigo' => strtoupper($this->faker->unique()->lexify('GER-???')),
            'descripcion' => $this->faker->sentence(),
            'tipo' => Gerencia::TIPO_GERENCIA ?? 'gerencia',
            'gerencia_padre_id' => null,
            'flujos_permitidos' => [],
            'orden' => 0,
            'activo' => true,
        ];
    }
}
