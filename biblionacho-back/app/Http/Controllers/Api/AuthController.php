<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * @OA\Info(
 *   title="Biblionacho API",
 *   version="1.0",
 *   description="Listado de URI'S para la API de Biblionacho",
 * )
 *
 * @OA\Server(
 *   url="http://127.0.0.1:8000/api"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Schema(
     *   schema="User",
     *   required={"first_name","last_name","email", "password", "password_confirmation"},
     *   @OA\Property(
     *     property="email",
     *     type="string",
     *     description="The user's email",
     *     example="admin@biblionacho.com"
     *   ),
     *
     *   @OA\Property(
     *     property="password",
     *     type="string",
     *     description="The user's password",
     *     example="biblionacho"
     *   ),
     * 
     *   @OA\Property(
     *     property="password_confirmation",
     *     type="string",
     *     description="The user's password confirmation",
     *     example="biblionacho"
     *   ),
     * 
     * @OA\Property(
     *     property="first_name",
     *     type="string",
     *     description="The user's name",
     *     example="Benjamin"
     *   ),
     * 
     * @OA\Property(
     *     property="last_name",
     *     type="string",
     *     description="The user's last_name",
     *     example="Franklin"
     *   ),
     * 
     *   @OA\RequestBody(
     *     required=true,
     *     description="Credencials to login",
     *     @OA\JsonContent(
     *       @OA\Property(property="email", type="string", example="admin@biblionacho.com"),
     *       @OA\Property(property="password", type="string", example="biblionacho")
     *     )
     *   ),
     * )
     *
     * @OA\Post(
     *   path="/auth/login",
     *   summary="Login for users",
     *   description="Login",
     *   operationId="login",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="User credentials",
     *     @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@biblionacho.com"),
     *       @OA\Property(property="password", type="string", format="password", example="biblionacho")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully logged in",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully logged in"),
     *       @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *       @OA\Property(property="token", type="string", example="Bearer token"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthorized"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}}),
     *     )
     *   ),
     *   
     *  @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $user = $request->user();
            $token = $user->createToken('AppToken')->plainTextToken;

            return response()->json([
                'message' => 'Successfully logged in',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *   path="/auth/register",
     *   summary="Register for users",
     *   description="Register",
     *   operationId="register",
     *   tags={"Auth"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="User registration",
     *     @OA\JsonContent(
     *       required={"first_name","last_name","email","password", "password_confirmation"},
     *       @OA\Property(property="first_name", type="string", example="John"),
     *       @OA\Property(property="last_name", type="string", example="Doe"),
     *       @OA\Property(property="email", type="string", format="email", example="user@biblionacho.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Successfully registered",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully registered"),
     *       @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}}),
     *     )
     *   ),
     * 
     * @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * )
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            $user->assignRole('guest');

            return response()->json([
                'message' => 'Successfully registered',
                'user' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *   path="/auth/logout",
     *   summary="Logout for authenticated users",
     *   description="Logout",
     *   operationId="logout",
     *   tags={"Auth"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     description="Bearer token",
     *     @OA\Schema(
     *       type="string",
     *       default="Bearer id|YOUR_ACCESS_TOKEN_HERE"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Successfully logged out",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully logged out"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Validation Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Validation Error"),
     *       @OA\Property(property="errors", type="object", example={"token": {"The token field is required."}}),
     *     )
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Internal Server Error",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Server Error"),
     *       @OA\Property(property="errors", type="object", example={"errors": {"Internal server error!"}}),
     *     )
     *   ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
