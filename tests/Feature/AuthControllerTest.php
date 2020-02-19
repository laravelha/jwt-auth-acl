<?php

namespace Laravelha\Auth\Tests\Feature;

use Laravelha\Auth\Models\User;
use Laravelha\Auth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const BASE_URL = '/api/auth';

    /**
     * @test
     */
    public function canLoginUser()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', self::BASE_URL . '/login', [
            'email'  => $user->email,
            'password'  => 'password',
        ]);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    }

    /**
     * @test
     */
    public function cannotLoginInvalidUser()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', self::BASE_URL . '/login', [
            'email'    =>  'non-existing-email@test.com',
            'password' => $user->password,
        ]);

        $response->assertStatus(401);
        $response->assertSeeText('Unauthorized');
        $response->assertJsonStructure(['error']);
    }

    /**
     * @test
     */
    public function canLogoutUser()
    {
        $response = $this->json('POST', self::BASE_URL . '/logout', [], $this->headers);

        $response->assertJsonPath('message', 'Successfully logged out');
    }

    /**
     * @test
     */
    public function canRefreshToken()
    {
        $response = $this->json('POST', self::BASE_URL . '/refresh', [], $this->headers);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    /**
     * @test
     */
    public function canGetAuthenticadedUser()
    {
        $response = $this->json('GET', self::BASE_URL . '/me', [], $this->headers);

        $response->assertJsonStructure([
            'user',
        ]);
    }
}
