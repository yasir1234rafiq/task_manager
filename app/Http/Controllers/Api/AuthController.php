<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $req)
    {
        // Validate incoming request
        $validateUser = Validator::make(
            $req->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:5|',
                'role'=>   'required'|'user,manager',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()->all(),
            ], 401);
        }
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'role'  =>  $req->role,
            'password' => Hash::make($req->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    // User Login
    public function login(Request $req)
    {
        // Validate incoming request
        $validateUser = Validator::make(
            $req->all(),
            [
                'email' => 'required|email',
                'password' => 'required|string',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()->all(),
            ], 401);
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
            $authuser = Auth::user();

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $authuser->createToken("API Token")->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email or password is not match',
            ], 401);
        }
    }


    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], 200);
    }
}

