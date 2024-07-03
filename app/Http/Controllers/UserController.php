<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showLoginUser()
    {
        try {
            $user = Auth::user();
            if ($user) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'user' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'No user login'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function editUpdate(Request $request, User $user)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'email' => 'string|email|max:255',
                'old_password' => 'string|min:8|nullable',
                'new_password' => 'string|min:8|nullable|confirmed',
            ]);

            if ($validasi->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => $validasi->messages()
                ], 422);
            }

            $updateData = [];

            if ($request->filled('name')) {
                $updateData['name'] = $request->name;
            }

            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->filled('old_password') && $request->filled('new_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Password lama salah'
                    ], 422);
                }

                $updateData['password'] = Hash::make($request->new_password);
            }

            $user->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => $th
            ], 500);
        }
    }

    public function deleteUser(User $user)
    {
        try {
            if (User::count() === 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete the last remaining user'
                ], 422);
            }
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => $th
            ], 500);
        }
    }
}
