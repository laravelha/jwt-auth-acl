<?php

namespace Laravelha\Auth\Http\Controllers;

use Exception;
use Laravelha\Auth\Http\Requests\UserStoreRequest;
use Laravelha\Auth\Http\Requests\UserUpdateRequest;
use Laravelha\Auth\Http\Resources\UserCollection;
use Laravelha\Auth\Http\Resources\UserResource;
use Laravelha\Auth\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"users"},
     *     summary="Display a listing of the resource",
     *     description="get all user on database and paginate then",
     *     path="/auth/users",
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
     *         response="200", description="List of users"
     *     )
     * )
     *
     * @return UserCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $limit = request()->has('limit') ? request()->get('limit') : null;
        return new UserCollection(User::paginate($limit));
    }

    /**
     * @OA\Post(
     *     tags={"users"},
     *     summary="Store a newly created resource in storage.",
     *     description="store a new user on database",
     *     path="/auth/users",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="email_verified_at", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="remember_token", type="string"),
     *       )
     *     ),
     *     @OA\Response(
     *         response="201", description="New user created"
     *     )
     * )
     *
     * @param  UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request)
    {
        return new UserResource(User::create($request->validated()));
    }

    /**
     * @OA\Get(
     *     tags={"users"},
     *     summary="Display the specified resource.",
     *     description="show user",
     *     path="/auth/users/{user}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="User id",
     *         in="path",
     *         name="user",
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
     *         response="200", description="Show user"
     *     )
     * )
     *
     * @param User $user
     * @return UserResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     tags={"users"},
     *     summary="Update the specified resource in storage",
     *     description="update a user on database",
     *     path="/auth/users/{user}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="User id",
     *         in="path",
     *         name="user",
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
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="email_verified_at", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="remember_token", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201", description="User updated"
     *     )
     * )
     *
     * @param  UserUpdateRequest $request
     * @param  User $user
     *
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     tags={"users"},
     *     summary="Remove the specified resource from storage.",
     *     description="remove a user on database",
     *     path="/auth/users/{user}",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         description="User id",
     *         in="path",
     *         name="user",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200", description="User deleted"
     *     )
     * )
     *
     * @param  User $user
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
