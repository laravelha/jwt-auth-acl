<?php

namespace Laravelha\Auth\Tests\Feature;

use Laravelha\Auth\Models\Permission;
use Laravelha\Auth\Models\Role;
use Laravelha\Auth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const BASE_URI = '/api/auth/roles';

    /**
     * @test
     */
    public function rolesListIsPaginated()
    {
        $count = Role::count();
        factory(Role::class, 30)->create();

        $this->assertCount($count + 30, Role::all());

        $response = $this->json('GET', self::BASE_URI, [], $this->headers);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'description',
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
    }

    /**
     * @test
     */
    public function roleCanBeCreated()
    {
        $this->withoutExceptionHandling();

        $count = Role::count();
        $roleFake = factory(Role::class)->make();

        $response = $this->json('POST', self::BASE_URI, $roleFake->toArray(), $this->headers);

        $response->assertStatus(201);

        $this->assertCount($count + 1, Role::all());

        $this->assertDatabaseHas('roles', $roleFake->getAttributes());
    }

    /**
     * @test
     */
    public function roleCanBeCreatedWithPermissions()
    {
        $count = Role::count();

        $roleFake = factory(Role::class)->make();
        $attributes = $roleFake->toArray();

        $permissions = factory(Permission::class, 10)->create();
        $attributes['permissions'] = $permissions->pluck('id')->values();

        $response = $this->json('POST', self::BASE_URI, $attributes, $this->headers);

        $response->assertStatus(201);

        $this->assertCount($count + 1, Role::all());

        $this->assertDatabaseHas('roles', $roleFake->getAttributes());

        $data = $response->decodeResponseJson()['data'];
        foreach ($attributes['permissions'] as $permissionId) {
            $this->assertDatabaseHas('permission_role', [
                'permission_id' => $permissionId,
                'role_id' => $data['id'],
            ]);
        }
    }

    /**
     * @test
     */
    public function roleCannotBeCreatedWithEmptyPermissions()
    {
        $roleFake = factory(Role::class)->make();
        $attributes = $roleFake->toArray();

        $attributes['permissions'] = [];

        $response = $this->json('POST', self::BASE_URI, $attributes, $this->headers);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('permissions');
    }

    /**
     * @test
     */
    public function roleCanBeDisplayed()
    {
        $roleFake = factory(Role::class)->make();
        $this->json('POST', self::BASE_URI, $roleFake->toArray(), $this->headers);

        $role  = Role::first();

        $response = $this->json('GET', self::BASE_URI . '/' . $role->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function roleCanBeUpdated()
    {
        $roleFakes = factory(Role::class, 2)->make();
        $this->json('POST', self::BASE_URI, $roleFakes->first()->toArray(), $this->headers);

        $role  = Role::first();

        $response = $this->json('PUT', self::BASE_URI . '/' . $role->id, $roleFakes->last()->toArray(), $this->headers);

        $response->assertStatus(200);

        $this->assertDatabaseHas('roles', $roleFakes->last()->getAttributes());
    }

    /**
     * @test
     */
    public function roleCanBeUpdatedWithPermissions()
    {
        $roleFakes = factory(Role::class, 2)->make();
        $attributes = $roleFakes->first()->toArray();

        $permissions = factory(Permission::class, 5)->create();
        $attributes['permissions'] = $permissions->pluck('id')->values();

        $this->json('POST', self::BASE_URI, $attributes, $this->headers);

        $role  = Role::first();

        $attributes = $roleFakes->last()->toArray();
        $permissions = factory(Permission::class, 5)->create();
        $attributes['permissions'] = $permissions->pluck('id')->values();

        $response = $this->json('PUT', self::BASE_URI . '/' . $role->id, $attributes, $this->headers);

        $response->assertStatus(200);

        $this->assertDatabaseHas('roles', $roleFakes->last()->getAttributes());

        $data = $response->decodeResponseJson()['data'];
        foreach ($attributes['permissions'] as $permissionId) {
            $this->assertDatabaseHas('permission_role', [
                'permission_id' => $permissionId,
                'role_id' => $data['id'],
            ]);
        }
    }

    /**
     * @test
     */
    public function roleCannotBeUpdatedWithEmptyPermissions()
    {
        $roleFakes = factory(Role::class, 2)->make();
        $attributes = $roleFakes->first()->toArray();

        $permissions = factory(Permission::class, 5)->create();
        $attributes['permissions'] = $permissions->pluck('id')->values();

        $this->json('POST', self::BASE_URI, $attributes, $this->headers);

        $role  = Role::first();

        $attributes = $roleFakes->last()->toArray();
        $attributes['permissions'] = [];

        $response = $this->json('PUT', self::BASE_URI . '/' . $role->id, $attributes, $this->headers);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('permissions');
    }

    /**
     * @test
     */
    public function roleCanBeDeleted()
    {
        $roleFake = factory(Role::class)->make();
        $count = Role::count();
        $this->json('POST', self::BASE_URI, $roleFake->toArray(), $this->headers);

        $this->assertCount($count + 1, Role::all());

        $role  = Role::all()->last();

        $response = $this->json('DELETE', self::BASE_URI . '/' . $role->id, [], $this->headers);

        $response->assertStatus(204);
        $this->assertCount($count, Role::all());

        $this->assertDatabaseMissing('roles', $roleFake->getAttributes());
    }
}
