<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Library\ApiResponse;
use Exception;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userId = auth()->id();
        $posts =  Post::with('user:id,name')->where('user_id', $userId)->get();
        return ApiResponse::success($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
        $authId = auth()->id();
        Post::create($request->validated() + ['user_id' => $authId]);
        ApiResponse::success(null, 'Post created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $authId = auth()->id();

        if ($authId != $post->user_id) {
            return ApiResponse::forbidden('This Post doesn\'t belongs to you.');
        }

        $post->update($request->validated());

        return ApiResponse::success(null, 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        $authId = auth()->id();

        if ($authId != $post->user_id) {
            return ApiResponse::forbidden('This Post doesn\'t belongs to you.');
        }

        $post->load('comments:id,comment');
        return ApiResponse::success(null, 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $authId = auth()->id();

        if ($authId != $post->user_id) {
            return ApiResponse::forbidden('This Post doesn\'t belongs to you.');
        }

        try {
            $post->delete();
            return ApiResponse::success(null, 'Post deleted successfully.');
        } catch (Exception $e) {
            return ApiResponse::exception($e);
        }
    }
}
