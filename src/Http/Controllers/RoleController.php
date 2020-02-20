<?php

namespace Laravelha\Auth\Http\Controllers;

use Exception;
use Laravelha\Auth\Http\Requests\RoleStoreRequest;
use Laravelha\Auth\Http\Requests\RoleUpdateRequest;
use Laravelha\Auth\Http\Resources\RoleCollection;
use Laravelha\Auth\Http\Resources\RoleResource;
use Laravelha\Auth\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"roles"},
     *     summary="Display a listing of the resource",
     *     description="get all role on database and paginate then",
     *     path="/auth/roles",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          name="only",
     *          in="query",
     *          description="filter results using field1;field2;field3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="search results using key:value",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="operators",
     *          in="query",
     *          description="set fileds operators using field1:operator1",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="sort",
     *          in="query",
     *          description="order results using field:direction",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="with",
     *          in="query",
     *          description="get relations using relation1;relation2;relation3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="define page",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="limit per page",
     *          @OA\Schema(type="int"),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200", description="List of roles"
     *     )
     * )
     *
     * @return RoleCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $limit = request()->has('limit') ? request()->get('limit') : null;
        return new RoleCollection(Role::paginate($limit));
    }

    /**
     * @OA\Post(
     *     tags={"roles"},
     *     summary="Store a newly created resource in storage.",
     *     description="store a new role on database",
     *     path="/auth/roles",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *       )
     *     ),
     *     @OA\Response(
     *         response="201", description="New role created"
     *     )
     * )
     *
     * @param  RoleStoreRequest $request
     * @return RoleResource
     */
    public function store(RoleStoreRequest $request)
    {
        return new RoleResource(Role::create($request->validated()));
    }

    /**
     * @OA\Get(
     *     tags={"roles"},
     *     summary="Display the specified resource.",
     *     description="show role",
     *     path="/auth/roles/{role}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Role id",
     *         in="path",
     *         name="role",
     *         required=true,
     *        @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="only",
     *          in="query",
     *          description="filter results using field1;field2;field3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="with",
     *          in="query",
     *          description="get relations using relation1;relation2;relation3...",
     *          @OA\Schema(type="string"),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200", description="Show role"
     *     )
     * )
     *
     * @param Role $role
     * @return RoleResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    /**
     * @OA\Put(
     *     tags={"roles"},
     *     summary="Update the specified resource in storage",
     *     description="update a role on database",
     *     path="/auth/roles/{role}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Role id",
     *         in="path",
     *         name="role",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201", description="Role updated"
     *     )
     * )
     *
     * @param  RoleUpdateRequest $request
     * @param  Role $role
     *
     * @return RoleResource
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role->update($request->validated());

        return new RoleResource($role);
    }

    /**
     * @OA\Delete(
     *     tags={"roles"},
     *     summary="Remove the specified resource from storage.",
     *     description="remove a role on database",
     *     path="/auth/roles/{role}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Role id",
     *         in="path",
     *         name="role",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Role deleted"
     *     )
     * )
     *
     * @param  Role $role
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(null, 204);
    }
}
