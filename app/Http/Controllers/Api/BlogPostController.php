<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{






    /**
     * Display the posts of a blog.
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

        $posts = Post::where('blog_id', $id)
            ->get();

        $blog->posts = $posts;

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'blog' => $blog,
        ]);
    }



}
