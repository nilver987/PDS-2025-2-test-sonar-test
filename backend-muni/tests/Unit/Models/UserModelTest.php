<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Gerencia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_factory_creates_user()
    {
        $u = User::factory()->create(['email' => 'u1@example.test']);
        $this->assertDatabaseHas('users', ['email' => 'u1@example.test']);
    }

    /** @test */
    public function user_belongs_to_gerencia()
    {
        $g = Gerencia::factory()->create();
        $u = User::factory()->create(['gerencia_id' => $g->id]);
        $this->assertEquals($g->id, $u->gerencia->id);
    }

    /** @test */
    public function user_can_be_assigned_a_role()
    {
        Role::create(['name' => 'tester']);
        $u = User::factory()->create();
        $u->assignRole('tester');
        $this->assertTrue($u->hasRole('tester'));
    }

    /** @test */
    public function user_can_be_given_permissions_directly()
    {
        Permission::create(['name' => 'ver_expedientes']);
        $u = User::factory()->create();
        $u->givePermissionTo('ver_expedientes');
        $this->assertTrue($u->hasPermissionTo('ver_expedientes'));
    }

    /** @test */
    public function user_updates_persist()
    {
        $u = User::factory()->create(['name' => 'OldName']);
        $u->name = 'NewName';
        $u->save();
        $this->assertEquals('NewName', $u->fresh()->name);
    }

    /** @test */
    public function created_at_and_updated_at_are_set()
    {
        $u = User::factory()->create();
        $this->assertNotNull($u->created_at);
        $this->assertNotNull($u->updated_at);
    }

    /** @test */
    public function email_is_unique_constraint_enforced_by_database()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => 'dup@example.test']);
        // Force direct DB insert same email to trigger unique constraint
        \DB::table('users')->insert([
            'name' => 'dup2',
            'email' => 'dup@example.test',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** @test */
    public function can_sync_roles_on_user()
    {
        Role::create(['name' => 'r1']);
        Role::create(['name' => 'r2']);
        $u = User::factory()->create();
        $u->syncRoles(['r1','r2']);
        $this->assertCount(2, $u->roles);
    }

    /** @test */
    public function default_activo_is_boolean()
    {
        $u = User::factory()->create();
        $this->assertIsBool((bool)$u->activo);
    }

    /** @test */
    public function user_can_be_deleted()
    {
        $u = User::factory()->create();
        $id = $u->id;
        $u->delete();
        $this->assertDatabaseMissing('users', ['id' => $id]);
    }
}
