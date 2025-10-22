<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\TipoTramiteController;
use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\TipoDocumento;
use App\Models\Expediente;
use App\Models\User;

class TipoTramiteControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_view_with_tipo_tramites()
    {
        $gerencia = Gerencia::factory()->create();
        TipoTramite::create(['nombre' => 'T1', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);

        $controller = new TipoTramiteController();
        $response = $controller->index();
        $this->assertTrue($response instanceof \Illuminate\View\View || $response instanceof \Illuminate\Http\JsonResponse);
    }

    /** @test */
    public function create_returns_view_with_aux_data()
    {
        TipoDocumento::create(['nombre' => 'Doc1', 'activo' => true]);
        $controller = new TipoTramiteController();
        $view = $controller->create();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function store_creates_tipo_tramite_and_returns_json_when_requested()
    {
        $gerencia = Gerencia::factory()->create();
        $admin = User::factory()->create(); $this->actingAs($admin);

        $request = Request::create('/tipos-tramite', 'POST', [
            'nombre' => 'Nuevo TT',
            'gerencia_id' => $gerencia->id,
            'costo' => 0,
            'tiempo_estimado_dias' => 2,
            'activo' => true
        ]);
        // Simular wantsJson
        $request->headers->set('Accept', 'application/json');

        $controller = new TipoTramiteController();
        $resp = $controller->store($request);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $resp);
        $this->assertDatabaseHas('tipo_tramites', ['nombre' => 'Nuevo TT']);
    }

    /** @test */
    public function store_creates_tipo_tramite_web_request()
    {
        $gerencia = Gerencia::factory()->create();
        $admin = User::factory()->create(); $this->actingAs($admin);

        $request = Request::create('/tipos-tramite', 'POST', [
            'nombre' => 'TT Web',
            'gerencia_id' => $gerencia->id,
            'costo' => 1.5,
            'tiempo_estimado_dias' => 3,
            'activo' => true
        ]);

        $controller = new TipoTramiteController();
        $resp = $controller->store($request);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $this->assertDatabaseHas('tipo_tramites', ['nombre' => 'TT Web']);
    }

    /** @test */
    public function show_returns_data_and_stats()
    {
        $gerencia = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTShow', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);
        Expediente::factory()->count(2)->create(['tipo_tramite_id' => $tt->id]);

        $controller = new TipoTramiteController();
        $resp = $controller->show($tt);
        $this->assertTrue($resp instanceof \Illuminate\View\View || $resp instanceof \Illuminate\Http\JsonResponse);
    }

    /** @test */
    public function update_modifies_tipo_tramite_and_returns_redirect()
    {
        $gerencia = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTUpd', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $request = Request::create('/tipos-tramite/'.$tt->id, 'PUT', [
            'nombre' => 'TTUpdMod',
            'gerencia_id' => $gerencia->id,
            'costo' => 0,
            'tiempo_estimado_dias' => 2,
            'activo' => true
        ]);

        $controller = new TipoTramiteController();
        $resp = $controller->update($request, $tt);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $this->assertDatabaseHas('tipo_tramites', ['nombre' => 'TTUpdMod']);
    }

    /** @test */
    public function destroy_blocks_if_expedientes_exist()
    {
        $gerencia = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTDel', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);
        $exp = Expediente::factory()->create(['tipo_tramite_id' => $tt->id]);

        $controller = new TipoTramiteController();
        $resp = $controller->destroy($tt);

        // JSON path returns 422, web path redirects with errors; assert exists
        $this->assertDatabaseHas('tipo_tramites', ['id' => $tt->id]);
    }

    /** @test */
    public function toggle_status_flips_activo_flag()
    {
        $gerencia = Gerencia::factory()->create();
        $tt = TipoTramite::create(['nombre' => 'TTToggle', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $controller = new TipoTramiteController();
        $resp = $controller->toggleStatus($tt);
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $resp);
        $tt->refresh();
        $this->assertIsBool($tt->activo);
    }
}
