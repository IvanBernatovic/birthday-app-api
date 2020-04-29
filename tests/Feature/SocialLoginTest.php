<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Mockery;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function mockSocialiteFacade($email = null, $name = null, $token = null, $id = null)
    {
        $socialiteUser = Mockery::mock(\Laravel\Socialite\Two\User::class);

        $socialiteUser->shouldReceive('getId')
            ->andReturn($id ?? '1234567890')
            ->shouldReceive('getEmail')
            ->andReturn($email ?? $this->faker->email)
            ->shouldReceive('getName')
            ->andReturn($name ?? $this->faker->name);

        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        $provider->expects($this->any())
            ->method('stateless')
            ->willReturn($provider);

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with our mock
        $this->app->instance(Socialite::class, $stub);
    }

    public function testGetErrorForUnssuportedSocialService()
    {
        $response = $this->get(route('social-login', 'randomservice'));

        $response->assertStatus(404);
    }

    public function testUserIsRedirectedToGoogleLogin()
    {
        $response = $this->get(route('social-login', 'google'));

        $this->assertStringContainsString('accounts.google.com/o/oauth2/auth', $response->getTargetUrl());
    }

    public function testUserCanSignInWithGoogle()
    {
        $this->withoutExceptionHandling();
        $email = $this->faker->email;
        $this->mockSocialiteFacade($email);

        $response = $this->get(route('social-login.callback', 'google'));

        $this->assertStringContainsString(config('app.client_url') . '/auth/social', $response->getTargetUrl());

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function testSocialLoginExceptionReturnsRedirect()
    {
        $stub = $this->createMock(Socialite::class);

        $stub->expects($this->any())
            ->method('driver')
            ->willThrowException(new Exception('Issue'));

        $response = $this->get(route('social-login.callback', 'google'));

        $this->assertStringContainsString(config('app.client_url'), $response->getTargetUrl());
        $this->assertStringContainsString('error', $response->getTargetUrl());
    }
}
