<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class DashboardControlller extends Controller
{
    public function index()
    {
        $stats = [
            'posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'categories' => Category::count(),
            'users' => User::count(),
        ];

        $recentPosts = Post::with('user', 'categories')->orderBy('created_at', 'desc')->take(5)->get();
        
        $popularCategories = Category::withCount('posts')->orderBy('posts_count', 'desc')->take(5)->get();

        return response()->json([
            'stats' => $stats,
            'recent_posts' => $recentPosts,
            'popular_categories' => $popularCategories,
        ]);


    }
}
