<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required']);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message'=>'Credenciales inválidas'], 401);
        }
        // revocar tokens anteriores si quieres: $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        $u = $request->user();
        return response()->json([
            'id'=>$u->id, 'nombre'=>$u->name, 'email'=>$u->email, 'role'=>$u->role
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['message'=>'ok']);
    }
}
