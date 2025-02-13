<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {

        //SOLO EL ADMIN PUEDE CREAR LOS USUARIOS
        try{
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->save();

            return response()->json($user,201);
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo crear el usuario'], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        //SOLO EL ADMIN Y EL USER PUEDEN ELIMINAR PUEDE EDITAR LOS USUARIOS
        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            if($user->id == auth()->id() || auth()->user()->isAdmin()){
                $post = User::findOrFail($user->id);
                $post->update([
                    'name' => $name !== null ? $name : $user->name,
                    'email' => $email !== null ? $email : $user->email,
                    'password' => $password !== null ? Hash::make($password) : $post->password,
                ]);
                return response()->json(['message' => 'Usuario editado']);
            }else{
                return response()->json(['message' => 'Solo el usuario o el administrador pueden editar este usuario'],401);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo editar el usuario'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       //SOLO EL ADMIN PUEDEN ELIMINAR LOS USUARIOS
        try{
            /*si tiene posts no se puede eliminar*/
            $hasPosts = $user->posts()->exists();
            if($hasPosts){
                return response()->json(['message' => 'El usuario tiene posts asociados']);
            }else{
                $user->delete();
                return response()->json(['message' => 'El usuario fue eliminado']);
            }
        } catch (\Exception $exception){
            return response()->json(['error' => 'No se pudo eliminar el usuario'], 500);
        }
    }
}
