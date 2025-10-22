<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Http\Controllers\RoleController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_view_with_roles_and_stats()
    {
        Role::create(['name' => 'rol1']);
        Permission::create(['name' => 'gestionar_usuarios']);

        $controller = new RoleController();
        $view = $controller->index();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function create_returns_view_with_permissions_grouped()
    {
        Permission::create(['name' => 'ver_expedientes']);
        $controller = new RoleController();
        $view = $controller->create();
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function store_creates_role_and_assigns_permissions()
    {
        $perm = Permission::create(['name' => 'ver_expedientes']);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $request = Request::create('/roles', 'POST', [
            'name' => 'role_test',
            'display_name' => 'Role Test',
            'permissions' => [$perm->id]
        ]);

        $controller = new RoleController();
        $response = $controller->store($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertDatabaseHas('roles', ['name' => 'role_test']);
    }

    /** @test */
    public function show_returns_view_with_role_and_stats()
    {
        $role = Role::create(['name' => 'rol_show']);
        $user = User::factory()->create();
        $user->assignRole($role->name);

        $controller = new RoleController();
        $view = $controller->show($role);
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function edit_returns_view_with_permissions()
    {
        $role = Role::create(['name' => 'rol_edit']);
        $controller = new RoleController();
        $view = $controller->edit($role);
        $this->assertInstanceOf(\Illuminate\View\View::class, $view);
    }

    /** @test */
    public function update_modifies_role_and_permissions()
    {
        $role = Role::create(['name' => 'rol_upd']);
        $perm = Permission::create(['name' => 'editar_expediente']);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $request = Request::create("/roles/{$role->id}", 'PUT', [
            'name' => 'rol_upd_new',
            'display_name' => 'Rol Actualizado',
            'permissions' => [$perm->id]
        ]);

        $controller = new RoleController();
        $response = $controller->update($request, $role);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertDatabaseHas('roles', ['name' => 'rol_upd_new']);
    }

    /** @test */
    public function destroy_blocks_critical_role_deletion()
    {
        $role = Role::create(['name' => 'administrador']);
        $admin = User::factory()->create(); $this->actingAs($admin);

        $controller = new RoleController();
        $response = $controller->destroy($role);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $this->assertDatabaseHas('roles', ['name' => 'administrador']);
    }

    /** @test */
    public function getData_returns_paginated_json()
    {
        Role::create(['name' => 'r1']);
        $request = Request::create('/roles/data', 'GET', ['search' => 'r']);
        $controller = new RoleController();
        $resp = $controller->getData($request);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $resp);
    }
}
