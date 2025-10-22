<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\TipoDocumento;

class DocumentoExpedienteFactory extends Factory
{
    protected $model = DocumentoExpediente::class;

    public function definition()
    {
        // Asegurar existencia de expediente y tipo de documento
        $exp = Expediente::first() ?? Expediente::factory()->create();
        $tipoDoc = TipoDocumento::first() ?? TipoDocumento::create(['nombre' => 'DocTipoTest', 'activo' => true]);

        // Nombre de archivo simulado y path que incluye el id del expediente
        $filename = 'test_' . $this->faker->unique()->word . '.txt';
        $path = 'documentos/expedientes/' . $exp->id . '/' . $filename;

        return [
            'expediente_id' => $exp->id,
            'nombre' => $this->faker->word,
            // Asegurar que tipo_documento no sea null para cumplir restricciones NOT NULL
            'tipo_documento' => $tipoDoc->id,
            'archivo' => $path,
            'extension' => pathinfo($filename, PATHINFO_EXTENSION),
            'tamaño' => 0,
            'mime_type' => 'text/plain',
            'usuario_subio_id' => $exp->usuario_registro_id ?? 1,
            'requerido' => false,
        ];
    }
}
