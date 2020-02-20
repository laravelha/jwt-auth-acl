<?php

namespace Laravelha\Auth\Tests\Feature;

use Laravelha\Auth\Models\Role;
use Laravelha\Auth\Models\User;
use Laravelha\Auth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const BASE_URI = '/api/auth/users';

    /**
     * @test
     */
    public function usersListIsPaginated()
    {
        $count = User::count();
        factory(User::class, 30)->create();

        $this->assertCount($count + 30, User::all());

        $response = $this->json('GET', self::BASE_URI, [], $this->headers);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'email',
                    'email_verified_at',
                    'password',
                    'remember_token',
                ]
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => [
                'current_page', 'last_page', 'from', 'to',
                'path', 'per_page', 'total'
            ]
        ]);
    }

    /**
     * @test
     */
    public function checkRequiredFields()
    {
        $response = $this->json('POST', self::BASE_URI, [], $this->headers);

        $response->assertJsonValidationErrors('name');
        $response->assertJsonValidationErrors('email');
        $response->assertJsonValidationErrors('password');
    }

    /**
     * @test
     */
    public function userCanBeCreated()
    {
        $userFake = factory(User::class)->make();

        $count = User::count();

        $response = $this->json('POST', self::BASE_URI, $userFake->toArray(), $this->headers);

        $response->assertStatus(201);

        $this->assertCount($count + 1, User::all());

        $this->assertDatabaseHas('users', $userFake->getAttributes());
    }

    /**
     * @test
     */
    public function userCanBeCreatedWithRoles()
    {
        $userFake = factory(User::class)->make();
        $attributes = $userFake->toArray();

        $roles = factory(Role::class, 10)->create();
        $attributes['roles'] = $roles->pluck('id')->values();

        $count = User::count();

        $response = $this->json('POST', self::BASE_URI, $attributes, $this->headers);

        $response->assertStatus(201);

        $this->assertCount($count + 1, User::all());

        $this->assertDatabaseHas('users', $userFake->getAttributes());

        $data = $response->decodeResponseJson()['data'];
        foreach ($attributes['roles'] as $roleId) {
            $this->assertDatabaseHas('role_user', [
                'role_id' => $roleId,
                'user_id' => $data['id'],
            ]);
        }
    }

    /**
     * @test
     */
    public function roleCannotBeCreatedWithEmptyRoles()
    {
        $userFake = factory(User::class)->make();
        $attributes = $userFake->toArray();

        $attributes['roles'] = [];

        $response = $this->json('POST', self::BASE_URI, $attributes, $this->headers);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('roles');
    }

    /**
     * @test
     */
    public function userCanBeDisplayed()
    {
        $userFake = factory(User::class)->make();
        $this->json('POST', self::BASE_URI, $userFake->toArray(), $this->headers);

        $user  = User::first();

        $response = $this->json('GET', self::BASE_URI . '/' . $user->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function userCanBeUpdated()
    {
        $this->withoutExceptionHandling();

        $userFakes = factory(User::class, 2)->make();
        $this->json('POST', self::BASE_URI, $userFakes->first()->toArray(), $this->headers);

        $user  = User::first();

        $response = $this->json('PUT', self::BASE_URI . '/' . $user->id, $userFakes->last()->toArray(), $this->headers);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', $userFakes->last()->getAttributes());
    }

    /**
     * @test
     */
    public function userCanBeDeleted()
    {
        $userFake = factory(User::class)->make();
        $count = User::count();
        $this->json('POST', self::BASE_URI, $userFake->toArray(), $this->headers);

        $this->assertCount($count + 1, User::all());

        $user  = User::all()->last();

        $response = $this->json('DELETE', self::BASE_URI . '/' . $user->id, [], $this->headers);

        $response->assertStatus(204);
        $this->assertCount($count, User::all());

        $this->assertDatabaseMissing('users', $userFake->getAttributes());
    }
}
