<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeBlogController extends Controller
{


    /**
     * Store a newly created comment (comments are posts with parent).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        //First check if blog exists

        $post_id = $request->post_id; //Parent Post
        $post = Post::find($post_id);
        if(!$post){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Post not found",
            ]);
        }


        $token = \request()->header('token');
        $user = User::where('token', $token)
            ->first();

        //check if user has liked post before
        $before = PostLike::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();


        if($before){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "You have already liked this post!",
            ]);
        }

        //Create a like
        $likes = new PostLike();
        $likes->user_id = $user->id;
        $likes->post_id = $post->id;
        $likes->save();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => "Post liked successfully "
        ]);

    }

}
