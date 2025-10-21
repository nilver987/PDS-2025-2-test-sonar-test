<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\WorkflowRule;

class WorkflowRuleBuilderTest extends TestCase
{
    /** @test */
    public function where_activo_is_rewritten_to_activa()
    {
        // Construimos una consulta usando el campo 'activo'
        $query = WorkflowRule::where('activo', true);

        // Obtenemos el SQL generado
        $sql = $query->toSql();

        // Laravel usa comillas dobles " para los identificadores en SQL
        $this->assertStringContainsString('"activa"', $sql);

        // Verificamos que 'activo' no aparezca en la consulta generada
        $this->assertStringNotContainsString('"activo"', $sql);
    }
}
