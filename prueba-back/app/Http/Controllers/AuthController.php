<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * receives user credentials and checks if it exists
     *
     * @param  Request  $request
     * @return JsonResponse
     */

    public function login(Request $request)
    {

        try {
            $credentials = $request->only(['email', 'password']);
            if (!$token = \JWTAuth::attempt($credentials)) {
                return response()->json([
                    'statusCode' => 401,
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'invalid credentials'
                ], 401);
            }
            $user = Auth::user();

            unset($user["email_verified_at"]);
            unset($user["created_at"]);
            unset($user["updated_at"]);

            $user = User::find($user->id);

            return response()->json([
                'statusCode' => 200,
                'code' => 'SUCCESS_LOGIN',
                'message' => 'user logged successfully',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_LOGIN',
                'message' => 'error login user',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */

    public function register(Request $request)
    {
        try {
            $encrypted_pass = Hash::make($request->password);
            $user = new User([
                'email' => $request->email,
                'password' => $encrypted_pass,
                'active' => $request->active,
            ]);
            $user->save();
            return response()->json([
                'statusCode' => 200,
                'code' => 'SUCCESS_REGISTER_USER',
                'message' => 'user saved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_REGISTER_USER',
                'message' => 'error saving user',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}