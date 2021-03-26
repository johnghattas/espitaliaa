<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;

class PatientAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {

        $validator = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:patients,email|unique:doctors,email',
            "password" => 'required|string|confirmed|min:6',
            'phone' => 'required|string|unique:patients,phone|max:11',
        ]);

        User::create(array_merge($validator, ['password' => bcrypt($validator['password'])]));

        if ($token = $this->guard()->attempt(['email' => $validator['email'], 'password' => $request->password])) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'done register but didn"t return the token'], 401);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $validator = request()->validate([
            'email' => 'required|email|max:255',
            "password" => 'required|string|min:6',
        ]);
        if (!$token = Auth::attempt($validator)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }
    /**
     * Get the guard of the admin
     *
     *
     * @return Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
