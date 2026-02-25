<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     *
     * GET /api/profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $this->withProfilePictureUrl($request->user());

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Update the authenticated user's profile.
     *
     * PUT/PATCH /api/profile
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => 'sometimes|required|email|unique:eis_users,email,' . $user->id,
            'first_name' => 'sometimes|nullable|string|max:255',
            'last_name' => 'sometimes|nullable|string|max:255',
            'username' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:50',
            'address' => 'sometimes|nullable|string|max:255',
            'date_of_birth' => 'sometimes|nullable|date',
            'gender' => 'sometimes|nullable|string|max:50',
            'profile_picture' => 'sometimes|nullable|string|max:2048',
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $this->withProfilePictureUrl($user),
        ]);
    }

    /**
     * Upload the authenticated user's avatar.
     *
     * POST /api/profile/avatar
     */
    public function avatar(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($user->profile_picture) {
            $this->deleteProfilePicture($user->profile_picture);
        }

        $path = $request->file('avatar')->store('profile-pictures', 'public');

        $user->profile_picture = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully',
            'data' => $this->withProfilePictureUrl($user),
        ]);
    }

    /**
     * Change the authenticated user's password.
     *
     * POST /api/profile/password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        if (Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Your new password cannot be the same as your old password.',
            ], 422);
        }

        $user->password = $validated['password'];
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }

    private function withProfilePictureUrl($user)
    {
        if (!$user || !$user->profile_picture) {
            return $user;
        }

        $value = $user->profile_picture;

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $user;
        }

        if (str_starts_with($value, '/storage/')) {
            $user->profile_picture = url($value);
            return $user;
        }

        $user->profile_picture = url(Storage::url($value));
        return $user;
    }

    private function deleteProfilePicture(string $value): void
    {
        $path = $value;

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            $parts = parse_url($value);
            $path = $parts['path'] ?? '';
        }

        if (str_starts_with($path, '/storage/')) {
            $path = ltrim(substr($path, strlen('/storage/')), '/');
        }

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
