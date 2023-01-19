<?php

namespace App\Http\Controllers;

use App\Library\ApiResponse;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        try {
            $post->comments()->create([
                'comment' => $request->comment,
                'user_id' => auth()->id()
            ]);
            return ApiResponse::success(null, 'Comment added successfully.');
        } catch (Exception $e) {
            return ApiResponse::exception($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $userId = auth()->id();
        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        if ($comment->user_id != $userId) {
            return ApiResponse::forbidden('This comment doesn\'t belongs to you.');
        }

        $comment->update(['comment' => $request->comment]);

        return ApiResponse::success(null, 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $userId = auth()->id();

        if ($comment->user_id != $userId) {
            return ApiResponse::forbidden('This comment doesn\'t belongs to you.');
        }

        try {
            $comment->delete();
            return ApiResponse::success(null, 'Comment deleted successfully.');
        } catch (Exception $e) {
            return ApiResponse::exception($e);
        }
    }
}
