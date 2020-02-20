<?php

namespace Laravelha\Auth\Http\Controllers;

use Laravelha\Auth\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     *  @OA\Post(
     *      tags={"auth"},
     *      summary="authenticate user by credentials",
     *      description="the user informs their credentials with email and password to get the access token",
     *      path="/auth/login",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *          )
     *     ),
     *     @OA\Response(
     *          response="200", description="Get Token JWT"
     *     )
     * )
     *
     * @param  AuthRequest  $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        if (! $token = auth('api')->attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     *  @OA\Post(
     *      tags={"auth"},
     *      summary="revoke user token",
     *      description="authenticated user request to revoke token",
     *      path="/auth/logout",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response="200", description="Logged out"
     *      ),
     *      @OA\Response(
     *          response="401", description="You are not authorized"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *      tags={"auth"},
     *      summary="refresh user token",
     *      description="authenticated user request to refresh token",
     *      path="/auth/refresh",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response="200", description="Refreshed"
     *      ),
     *      @OA\Response(
     *          response="401", description="You are not authorized"
     *      )
     * )
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @OA\Get(
     *      tags={"auth"},
     *      summary="get auth user",
     *      description="get authenticated user data",
     *      path="/auth/me",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response="200", description="Successful"
     *      ),
     *      @OA\Response(
     *          response="401", description="You are not authorized"
     *      )
     * )
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function me(): JsonResponse
    {
        return response()->json(['user' => auth('api')->user()]);
    }

    /**
     * @param  string  $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
