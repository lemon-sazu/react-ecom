<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validate_errors' => $validator->messages()
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken($user->email . '_token', [''])->plainTextToken;

            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Registration Successfully',
            ]);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_error' => $validator->messages()
            ]);
        } else {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credentials'
                ]);
            } else {
                if ($user->role == 'Admin') {
                    $token = $user->createToken($user->email . 'admin_token', ['server:admin'])->plainTextToken;
                } else {
                    $token = $user->createToken($user->email . '_token', [''])->plainTextToken;
                }
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'role' => $user->role,
                    'token' => $token,
                    'message' => 'Login In Successfull'
                ]);
            }
        }
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged Out Successfully.'
        ]);
    }
}
