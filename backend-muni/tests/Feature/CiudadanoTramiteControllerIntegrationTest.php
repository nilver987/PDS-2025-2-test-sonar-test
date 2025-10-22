<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\CiudadanoTramiteController;
use App\Models\Gerencia;
use App\Models\TipoTramite;
use App\Models\User;
use App\Models\Expediente;
use Spatie\Permission\Models\Role;

class CiudadanoTramiteControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_shows_available_tipos_tramite()
    {
        $gerencia = Gerencia::factory()->create();
        TipoTramite::create(['nombre' => 'Tramite Test', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);

        $controller = new CiudadanoTramiteController();
        $view = $controller->index();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $data = $view->getData();
        $this->assertArrayHasKey('tiposTramite', $data);
    }

    /** @test */
    public function create_redirects_if_no_tipo_tramite_selected()
    {
        $controller = new CiudadanoTramiteController();
        $response = $controller->create(new Request());
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function create_shows_form_when_tipo_present()
    {
        $gerencia = Gerencia::factory()->create();
        $tipo = TipoTramite::create(['nombre' => 'TT', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);

        $request = Request::create('/ciudadano/tramites/create?tipo_tramite_id='.$tipo->id, 'GET');
        $controller = new CiudadanoTramiteController();
        $view = $controller->create($request);
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function store_creates_expediente_minimal()
    {
        Role::create(['name' => 'ciudadano']);
        $user = User::factory()->create();
        $user->assignRole('ciudadano');
        $this->actingAs($user);

        $gerencia = Gerencia::factory()->create();
        $tipo = TipoTramite::create(['nombre' => 'Tramitito', 'gerencia_id' => $gerencia->id, 'activo' => true, 'costo' => 0, 'tiempo_estimado_dias' => 1]);

        $request = Request::create('/ciudadano/tramites', 'POST', [
            'tipo_tramite_id' => $tipo->id,
            'asunto' => 'Asunto prueba',
            'descripcion' => 'Descripción prueba'
        ]);

        $controller = new CiudadanoTramiteController();
        $response = $controller->store($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertDatabaseHas('expedientes', ['tipo_tramite_id' => $tipo->id, 'asunto' => 'Asunto prueba']);
    }

    /** @test */
    public function mis_tramites_returns_view_with_expedientes()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $exp = Expediente::factory()->create(['usuario_registro_id' => $user->id]);

        $controller = new CiudadanoTramiteController();
        $view = $controller->misTramites();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function show_displays_expediente_for_owner()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $exp = Expediente::factory()->create(['usuario_registro_id' => $user->id]);

        $controller = new CiudadanoTramiteController();
        $view = $controller->show($exp->id);
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }


}
