<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Gerencia;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_belongs_to_gerencia()
    {
        $gerencia = Gerencia::factory()->create();
        $user = User::factory()->create(['gerencia_id' => $gerencia->id]);

        $this->assertNotNull($user->gerencia);
        $this->assertEquals($gerencia->id, $user->gerencia->id);
    }

    /** @test */
    public function user_can_be_assigned_roles()
    {
        $role = Role::create(['name' => 'tester']);
        $user = User::factory()->create();
        $user->assignRole('tester');

        $this->assertTrue($user->hasRole('tester'));
    }
}
