<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new AuthController();
    }

    /** @test */
    public function it_validates_login_request()
    {
        $request = new Request([
            'email' => 'invalid-email',
            'password' => ''
        ]);

        $response = $this->controller->login($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_validates_register_request()
    {
        $request = new Request([
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123'
        ]);

        $response = $this->controller->register($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_validates_change_password_request()
    {
        $request = new Request([
            'current_password' => '123',
            'new_password' => ''
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->controller->changePassword($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function it_checks_current_password_matches()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password')
        ]);

        $this->actingAs($user);
        
        $request = new Request([
            'current_password' => 'wrong-password',
            'new_password' => 'new-password',
            'new_password_confirmation' => 'new-password'
        ]);

        $response = $this->controller->changePassword($request);
        $this->assertEquals(400, $response->getStatusCode());
    }

    /** @test */
    public function it_validates_email_availability()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $request = new Request(['email' => 'test@example.com']);
        $response = $this->controller->checkEmail($request);

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['data']['disponible']);
    }
}
