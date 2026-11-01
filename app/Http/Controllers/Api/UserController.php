<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // READ: Menampilkan semua user
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil',
            'data' => $users
        ], 200);
    }

    // READ: Menampilkan satu user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    // CREATE: Menambahkan user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    // UPDATE: Mengubah user
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8'
        ]);

        if (isset($validated['username'])) {
            $user->username = $validated['username'];
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data' => $user
        ], 200);
    }

    // DELETE: Menghapus user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ], 200);
    }
}
