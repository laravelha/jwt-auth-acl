<?php

namespace Laravelha\Auth\Http\Controllers;

use Exception;
use Laravelha\Auth\Http\Requests\PermissionStoreRequest;
use Laravelha\Auth\Http\Requests\PermissionUpdateRequest;
use Laravelha\Auth\Http\Resources\PermissionCollection;
use Laravelha\Auth\Http\Resources\PermissionResource;
use Laravelha\Auth\Models\Permission;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"permissions"},
     *     summary="Display a listing of the resource",
     *     description="get all permission on database and paginate then",
     *     path="/auth/permissions",
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
     *         response="200", description="List of permissions"
     *     )
     * )
     *
     * @return PermissionCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize(request()->route()->getName());

        $limit = request()->has('limit') ? request()->get('limit') : null;
        return new PermissionCollection(Permission::paginate($limit));
    }

    /**
     * @OA\Post(
     *     tags={"permissions"},
     *     summary="Store a newly created resource in storage.",
     *     description="store a new permission on database",
     *     path="/auth/permissions",
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
     *         response="201", description="New permission created"
     *     )
     * )
     *
     * @param  PermissionStoreRequest $request
     * @return PermissionResource
     */
    public function store(PermissionStoreRequest $request)
    {
        return new PermissionResource(Permission::create($request->validated()));
    }

    /**
     * @OA\Get(
     *     tags={"permissions"},
     *     summary="Display the specified resource.",
     *     description="show permission",
     *     path="/auth/permissions/{permission}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Permission id",
     *         in="path",
     *         name="permission",
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
     *         response="200", description="Show permission"
     *     )
     * )
     *
     * @param Permission $permission
     * @return PermissionResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Permission $permission)
    {
        $this->authorize(request()->route()->getName());

        return new PermissionResource($permission);
    }

    /**
     * @OA\Put(
     *     tags={"permissions"},
     *     summary="Update the specified resource in storage",
     *     description="update a permission on database",
     *     path="/auth/permissions/{permission}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Permission id",
     *         in="path",
     *         name="permission",
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
     *         response="201", description="Permission updated"
     *     )
     * )
     *
     * @param  PermissionUpdateRequest $request
     * @param  Permission $permission
     *
     * @return PermissionResource
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return new PermissionResource($permission);
    }

    /**
     * @OA\Delete(
     *     tags={"permissions"},
     *     summary="Remove the specified resource from storage.",
     *     description="remove a permission on database",
     *     path="/auth/permissions/{permission}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="Permission id",
     *         in="path",
     *         name="permission",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="Permission deleted"
     *     )
     * )
     *
     * @param  Permission $permission
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Permission $permission)
    {
        $this->authorize(request()->route()->getName());

        $permission->delete();

        return response()->json(null, 204);
    }
}
