<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCommentController extends Controller
{


    /**
     * Create new comment (comments are posts with parent).
     * @param $post_id, $body, $image
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
            'body' => 'required|min:5|max:1000',
            'image' => 'sometimes|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        //First check if blog exists

        $parent_id = $request->post_id; //Parent Post
        $post = Post::find($parent_id);
        if(!$post){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Post not found",
            ]);
        }
        $blog = Blog::find($post->blog_id);

        if(!$blog){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog not found",
            ]);
        }

        $token = \request()->header('token');
        $user = User::where('token', $token)
            ->first();


        //Create a new comment (post)
        $post = new Post();
        $post->body = $request->body;
        $post->user_id = $user->id;
        $post->blog_id = $blog->id;
        $post->parent_id = $parent_id;
        $post->save();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => "Comment created successfully",
            'comment' => $post,
        ]);

    }

}
