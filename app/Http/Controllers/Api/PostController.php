<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display all posts
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'posts' => $posts,
        ]);
    }


    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required',
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

        $blog_id = $request->blog_id;
        $blog = Blog::find($blog_id);

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

        //create new post

        //Create a new post
        $post = new Post();
        $post->body = $request->body;
        $post->user_id = $user->id;
        $post->blog_id = $blog->id;
        $post->parent_id = 0;
        $post->save();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => "post created successfully",
            'post' => $post,
        ]);

    }

    /**
     * Display the specified blog.
     */
    public function show(string $id)
    {
        $post = Post::where('id',$id)->with('likes','comments')->first();

        if(!$post){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Post not found",
            ]);
        }

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'post' => $post,
        ]);
    }


    /**
     * Update the specified blog post
     */
    public function update(Request $request, string $id)
    {
        $token = \request()->header('token');
        $user = User::where('token', $token)
            ->first();

        //fetch the blog
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Post not found",
            ]);
        }

        if($post->user_id != $user->id){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Post not found",
            ]);
        }

        $validator = Validator::make($request->all(), [
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




        $post->body = $request->body;
        if($request->image && $request->image != ""){
            $post->image = $request->image;
        }
        $post->save();


        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => "Post updated successfully",
            'post' => $post,
        ]);
    }


    /**
     * Remove the specified post.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog not found",
            ]);
        }

        $token = \request()->header('token');
        $user = User::where('token', $token)
            ->first();

        if($post->user_id == $user->id) {
            $post->delete();
            return response()->json([
                'code' => 200,
                'status' => 'ok',
                'message' => "Blog post deleted successfully",
            ]);
        }else{
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog post not found",
            ]);
        }
    }
}
