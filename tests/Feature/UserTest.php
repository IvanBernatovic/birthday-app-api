<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AttachJwtToken;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, AttachJwtToken;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanSignUp()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'email@test.com',
            'password' => 'password',
        ];

        $response = $this->post('api/register', $userData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    public function testUserCanSignIn()
    {
        $user = factory(User::class)->create();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function testUserHasToBeLoggedInForProtectedRoute()
    {
        $this->patch(route('users.update', [], false), [
            'name' => 'Test Name',
        ])->assertStatus(401);
    }

    public function testUserCanRefreshToken()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->post('api/refresh');

        $response->assertStatus(200);
    }

    public function testUserCanGetHisData()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->get('api/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.email', $user->email);
    }

    public function testUserCanSignOut()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->get('api/me')->assertStatus(200);
        $this->post('api/logout')->assertStatus(200);

        // confirm that JWT token is invalid
        $this->get('api/me')->assertStatus(302);
    }

    public function testUserCanGetUnauthorized()
    {
        $response = $this->post('api/login', [
            'email' => 'doesnt_exist@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function testUserCanUpdateHisData()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->patch(route('users.update', [], false), [
                'name' => 'Test',
            ])
            ->assertStatus(200);

        $user->refresh();
        $this->assertEquals('Test', $user->name);
    }
}
