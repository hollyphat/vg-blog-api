<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display all blogs
     */
    public function index()
    {
        $blogs = Blog::all();
        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'blogs' => $blogs,
        ]);
    }



    /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:250',
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

        $token = \request()->header('token');
        $user = User::where('token', $token)
            ->first();

        //create new blog
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->image = $request->image;
        $blog->user_id = $user->id;
        $blog->save();

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
            'message' => "Blog created successfully",
            'blog' => $blog,
        ]);

    }

    /**
     * Display the specified blog.
     */
    public function show(string $id)
    {
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog not found",
            ]);
        }

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'blog' => $blog,
        ]);
    }



    /**
     * Update the specified blog
     */
    public function update(Request $request, string $id)
    {
        $token = \request()->header('token');
        $user = User::where('token', $token)
            ->first();

        //fetch the blog
        $blog = Blog::find($id);

        if(!$blog){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog not found",
            ]);
        }

        if($blog->user_id != $user->id){
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog not found",
            ]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:250',
            'image' => 'sometimes|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }




        $blog->title = $request->title;
        if($request->image && $request->image != ""){
            $blog->image = $request->image;
        }
        $blog->save();


        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => "Blog updated successfully",
            'blog' => $blog,
        ]);
    }

    /**
     * Remove the specified blog.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);

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

        if($blog->user_id == $user->id) {
            $blog->delete();
            return response()->json([
                'code' => 200,
                'status' => 'ok',
                'message' => "Blog deleted successfully",
            ]);
        }else{
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => "Blog not found",
            ]);
        }
    }
}
