<?php

namespace Laravelha\Auth\Tests\Feature;

use Laravelha\Auth\Models\Permission;
use Laravelha\Auth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PermissionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private const BASE_URI = '/api/auth/permissions';

    /**
     * @test
     */
    public function permissionsListIsPaginated()
    {
        $count = Permission::count();
        factory(Permission::class, 30)->create();

        $this->assertCount($count + 30, Permission::all());

        $response = $this->json('GET', self::BASE_URI, [], $this->headers);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'action',
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
        $response->assertJsonValidationErrors('action');
    }

    /**
     * @test
     */
    public function permissionCanBeCreated()
    {
        $count = Permission::count();
        $permissionFake = factory(Permission::class)->make();

        $response = $this->json('POST', self::BASE_URI, $permissionFake->toArray(), $this->headers);

        $response->assertStatus(201);

        $this->assertCount($count + 1, Permission::all());

        $this->assertDatabaseHas('permissions', $permissionFake->getAttributes());
    }

    /**
     * @test
     */
    public function permissionCanBeDisplayed()
    {
        $permissionFake = factory(Permission::class)->make();
        $this->json('POST', self::BASE_URI, $permissionFake->toArray(), $this->headers);

        $permission  = Permission::first();

        $response = $this->json('GET', self::BASE_URI . '/' . $permission->id);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function permissionCanBeUpdated()
    {
        $permissionFakes = factory(Permission::class, 2)->make();
        $this->json('POST', self::BASE_URI, $permissionFakes->first()->toArray(), $this->headers);

        $permission  = Permission::first();

        $lastPermissionArray = $permissionFakes->last()->toArray();

        $response = $this->json('PUT', self::BASE_URI . '/' . $permission->id, $lastPermissionArray, $this->headers);

        $response->assertStatus(200);

        $this->assertDatabaseHas('permissions', $permissionFakes->last()->getAttributes());
    }

    /**
     * @test
     */
    public function permissionCanBeDeleted()
    {
        $permissionFake = factory(Permission::class)->make();
        $count = Permission::count();
        $this->json('POST', self::BASE_URI, $permissionFake->toArray(), $this->headers);

        $this->assertCount($count + 1, Permission::all());

        $permission  = Permission::all()->last();

        $response = $this->json('DELETE', self::BASE_URI . '/' . $permission->id, [], $this->headers);

        $response->assertStatus(204);
        $this->assertCount($count, Permission::all());

        $this->assertDatabaseMissing('permissions', $permissionFake->getAttributes());
    }
}
