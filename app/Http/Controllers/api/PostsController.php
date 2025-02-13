<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Resources\PostResource;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Posts::all();
        return response()->json(PostResource::collection($posts), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request): JsonResponse
    {
        try {
            $post = new Posts();
            $post->title = $request->get('title');
            $post->body = $request->get('body');
            $post->user()->associate(auth()->id());
            $post->save();

            return response()->json($post,201);
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo crear el post'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Posts $post): JsonResponse
    {
        return response()->json(new PostResource($post), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Posts $post): JsonResponse
    {
        $title = $request->get('title');
        $body = $request->get('body');

        try {
            if($post->user->id == auth()->id() || auth()->user()->isAdmin()){
                $post = Posts::findOrFail($post->id);
                $post->update([
                    'title' => $title !== null ? $title : $post->title,
                    'body' => $body !== null ? $body : $post->body,
                ]);
                return response()->json(['message' => 'Post editado', 'post' => $post], 200);
            }else{
                return response()->json(['message' => 'Solo el propietario o el administrador pueden editar el post'],401);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo editar el post'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posts $post): JsonResponse
    {
        try {
            /*Solo el admin o el dueño del post pueden eliminar*/
            if($post->user->id == auth()->id() || auth()->user()->isAdmin()){
                $post->delete();
                return response()->json(['message' => 'Post eliminado']);
            }else{
                return response()->json(['message' => 'Solo el propietario o el administrador pueden eliminar el post'],401);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo eliminar el post'], 500);
        }
    }

    public function getPostsByUser(Request $request, User $user): JsonResponse
    {
        try {
            $posts = $user->posts;
            return response()->json(PostResource::collection($posts), 200);
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo eliminar el post'], 500);
        }
    }
}
