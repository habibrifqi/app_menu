<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Sembunyikan field yang tidak ingin ditampilkan
        $user->makeHidden(['remember_token', 'created_at', 'updated_at', 'deleted_at']);

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'data' => [
                'user'  => $user,
                'token' => $token,
                'type'  => 'Bearer',
            ],
        ]);
    }

    public function me(Request $request){
        $user = auth()->user();
        $user->makeHidden(['remember_token', 'created_at', 'updated_at', 'deleted_at']);
        return response()->json([
            'data' => $user
        ]);
    }
}
