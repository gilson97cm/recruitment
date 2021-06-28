<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user = User::all();
        return response()->json([
            'statusCode' => 200,
            'code' => 'ALL_USERS',
            'user' => $user
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $user = User::find($id);

        if (isset($user)) {
            return response()->json([
                'statusCode' => 200,
                'code' => 'USER_FOUND',
                'message' => 'user found',
                'user' => $user
            ], 200);
        }
        return response()->json([
            'statusCode' => 404,
            'code' => 'USER_NOT_FOUND',
            'message' => 'error finding user',
            'user' => $user,
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $user = User::find($id);
            $user->email = $request->email;
            if($request->password != ''){
                $encrypted_pass = Hash::make($request->password);
                $user->password = $encrypted_pass;
            }
            $user->save();

            return response()->json([
                'statusCode' => 200,
                'code' => 'SUCCESS_UPDATE_USER',
                'message' => 'user updated successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_UPDATE_USER',
                'message' => 'error updating user',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (isset($user)) {
                $user->delete();
                return response()->json([
                    'statusCode' => 200,
                    'code' => 'SUCCESS_DELETE_USER',
                    'message' => 'user deleted successfully',
                ], 200);
            }
            return response()->json([
                'statusCode' => 404,
                'code' => 'USER_NOT_FOUND',
                'message' => 'error finding user',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_DELETE_USER',
                'message' => 'error deleting user',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

}
