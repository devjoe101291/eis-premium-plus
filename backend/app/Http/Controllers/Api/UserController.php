<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * GET /api/eis-users
     */
    public function index(): JsonResponse
    {
        $users = User::paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * (Typically not used in APIs, kept for CRUD completeness)
     */
    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Create Eis User endpoint',
        ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * POST /api/eis-users
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:eis_users,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data'    => $user,
        ], 201);
    }

    /**
     * Display the specified user.
     *
     * GET /api/eis-users/{id}
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * (Typically not used in APIs, kept for CRUD completeness)
     */
    public function edit(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Edit Eis User endpoint',
            'user_id' => $id,
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * PUT /api/eis-users/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $validated = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|email|unique:eis_users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data'    => $user,
        ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * DELETE /api/eis-users/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
