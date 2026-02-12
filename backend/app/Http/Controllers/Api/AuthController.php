<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // or EisUser if you're using that model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
      public function me(Request $request)
    {
        $user = $request->user();
        // $user->load('details'); // Load user details relationship
        return response()->json($user);
    }
  
    /**
     * Login user (Vue.js → Laravel)
     */
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials',
    //         ], 401);
    //     }

    //     $token = $user->createToken('vue-auth')->plainTextToken;

    //     return response()->json([
    //         'success' => true,
    //         'token'   => $token,
    //         'user'    => $user,
    //     ]);
    // }

public function login(Request $request)
    {
        // Validate that role is provided
        $request->validate([
            'role' => 'required|in:admin,employee',
        ]);

        // Map role to preset credentials
        $credentials = [
            'admin' => ['email' => 'admin@example.com', 'password' => 'proweaver'],
            'employee' => ['email' => 'employee@example.com', 'password' => 'proweaver'],
        ];

        $role = $request->role;
        $email = $credentials[$role]['email'];
        $password = $credentials[$role]['password'];

        // Find user in DB
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials for selected role',
            ], 401);
        }

        // Create token
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
            'role' => $role,
        ]);
    }



    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out',
        ]);
    }


}
