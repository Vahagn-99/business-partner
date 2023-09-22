<?php

namespace App\Http\Controllers\Api\Auth;

use App\DTO\Validations\Auth\LoginUserData;
use App\DTO\Validations\Auth\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Operations related to user authentication"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="User registration",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string"),
     *         )
     *     ),
     *     @OA\Response(response="400", description="Bad request"),
     * )
     */
    public function register(RegisterUserData $data): JsonResponse
    {
        $validation = $data->all();

        $validation['password'] = Hash::make($validation['password']);

        /** @var User $user */
        $user = User::query()->create($validation);

        $token = $user->createToken('sanctum-token')->plainTextToken;

        return response()->json(['access_token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string"),
     *          )
     *      ),
     *     @OA\Response(response="401", description="Authentication failed"),
     * )
     * @throws ValidationException
     */
    public function login(LoginUserData $data): JsonResponse|Authenticatable|null
    {
        if (!Auth::attempt($data->all())) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('sanctum-token')->plainTextToken;

        return response()->json(['access_token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="204", description="User logged out successfully"),
     * )
     */
    public function logout(Request $request): Response
    {
        $request->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->noContent();
    }
}
