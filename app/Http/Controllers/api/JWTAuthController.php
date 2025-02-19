<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'Credenciales invalidas'], 400);
            }
            $user = auth()->user();

            return response()->json(compact('token'));
        }catch (JWTException $e){
            return response()->json(['error'=> 'No se pudo crear el token'], 500);
        }
    }

    public function register(Request $request){
        $validar = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validar->fails()){
            return response()->json($validar->errors(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function getUser(Request $request){
        try {
            if(! $user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
        }catch (JWTException $e){
            return response()->json(['error' => 'Token Invalido'], 500);
        }

        return response()->json(compact('user'));
    }

    public function logout(Request $request){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout exitoso'], 200);
    }
}
