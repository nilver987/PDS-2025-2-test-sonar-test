<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\UsuarioController;
use App\Models\User;
use App\Models\Gerencia;
use Spatie\Permission\Models\Role;

class UsuarioControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_view_with_users_and_aux_data()
    {
        // Crear datos
        $gerencia = Gerencia::factory()->create();
        User::factory()->count(3)->create(['gerencia_id' => $gerencia->id]);

        $controller = new UsuarioController();
        $request = Request::create('/usuarios', 'GET');

        $view = $controller->index($request);

        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $data = $view->getData();

        $this->assertArrayHasKey('usuarios', $data);
        $this->assertArrayHasKey('roles', $data);
        $this->assertArrayHasKey('gerencias', $data);
    }

    /** @test */
    public function create_returns_view_with_roles_and_gerencias()
    {
        $controller = new UsuarioController();
        $view = $controller->create();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $data = $view->getData();
        $this->assertArrayHasKey('roles', $data);
        $this->assertArrayHasKey('gerencias', $data);
    }

    /** @test */
    public function store_creates_user_and_assigns_roles()
    {
        // Crear rol que será asignado
        Role::create(['name' => 'administrador']);

        $admin = User::factory()->create(); // usuario que ejecuta acción (middleware bypass al llamar método directo)
        $this->actingAs($admin);

        $gerencia = Gerencia::factory()->create();

        $requestData = [
            'name' => 'Usuario Prueba',
            'email' => 'usuario.prueba@example.test',
            'dni' => '12345678',
            'telefono' => '987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gerencia_id' => $gerencia->id,
            'roles' => ['administrador'],
            'estado' => 'activo',
        ];

        $request = Request::create('/usuarios', 'POST', $requestData);

        $controller = new UsuarioController();
        $response = $controller->store($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

        $this->assertDatabaseHas('users', [
            'email' => 'usuario.prueba@example.test',
            'dni' => '12345678',
        ]);

        $created = User::where('email', 'usuario.prueba@example.test')->first();
        $this->assertNotNull($created);
        $this->assertTrue($created->hasRole('administrador'));
    }

    /** @test */
    public function show_returns_view_with_usuario()
    {
        $user = User::factory()->create();
        $controller = new UsuarioController();
        $view = $controller->show($user);
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $data = $view->getData();
        $this->assertArrayHasKey('usuario', $data);
    }

    /** @test */
    public function edit_returns_view_with_data()
    {
        $user = User::factory()->create();
        $controller = new UsuarioController();
        $view = $controller->edit($user);
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
        $data = $view->getData();
        $this->assertArrayHasKey('roles', $data);
        $this->assertArrayHasKey('gerencias', $data);
    }

    /** @test */
    public function update_changes_user_and_syncs_roles()
    {
        Role::create(['name' => 'editor']);
        $user = User::factory()->create(['estado' => 'activo']);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $requestData = [
            'name' => 'Nombre Modificado',
            'email' => $user->email,
            'dni' => $user->dni,
            'telefono' => '000000',
            'password' => '',
            'gerencia_id' => null,
            'roles' => ['editor'],
            'estado' => 'activo',
        ];

        $request = Request::create('/usuarios/'.$user->id, 'PUT', $requestData);
        $controller = new UsuarioController();
        $response = $controller->update($request, $user);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $user->refresh();
        $this->assertEquals('Nombre Modificado', $user->name);
        $this->assertTrue($user->hasRole('editor'));
    }

    /** @test */
    public function destroy_protects_self_and_prevents_deletion()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $controller = new UsuarioController();
        $response = $controller->destroy($user);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function toggle_status_switches_estado()
    {
        $user = User::factory()->create(['estado' => 'activo']);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $controller = new UsuarioController();
        $response = $controller->toggleStatus($user);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $user->refresh();
        $this->assertTrue(in_array($user->estado, ['activo', 'inactivo']));
    }
}
