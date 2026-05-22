<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'categories'])->orderBy('created_at', 'desc');

        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        $posts = $query->paginate(10);
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $post = new Post();
            $post->title = $request->title ?? 'Untitled Post';

            $originalSlug = Str::slug($post->title);
            $slug = $originalSlug;
            $counter = 1;

            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $post->slug = $slug;
            $post->description = $request->description ?? '';
            $post->content = $request->content ?? '';
            $post->status = 'draft';
            $post->user_id = Auth::id();
            $post->save();

            if ($request->has('categories')) {
                $post->categories()->attach($request->categories);
            }

            return response()->json($post->load('categories', 'user'));
        } catch (\Exception $e) {
            \Log::error('Post creation failed: ' .  $e->getMessage());
            return response()->json(['message' => 'Failed to create post:' .  $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (!empty($post->image)) {
            if (strpos($post->image, 'http') !== 0 && strpos($post->image, '/storage') !== 0) {
                $post->image = asset('storage/' . $post->image);
            }

            if (strpos($post->image, '/storage//storage/') !== false) {
                $post->image = str_replace('/storage//storage/', '/storage/', $post->image);
            }
        }

        return response()->json($post->load('categories', 'user'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3',
            'description' => 'required|string|min:10',
            'content' => 'required|string|min:20',
            'image' => 'nullable|string',
            'categories' => 'nullable|array',
        ], [
            'title.required' => 'Please enter a post title.',
            'title.min' => 'Title must be at least 3 characters.',
            'description.required' => 'Please provide a brief description.',
            'description.min' => 'Description should be at least 10 characters.',
            'content.required' => 'Post content cannot be empty.',
            'content.min' => 'Post content should be at least 20 characters.',
        ]);

        try {
            $post->title = $validated['title'];
            if ($post->isDirty('title')) {
                $originalSlug = Str::slug($validated['title']);
                $slug = $originalSlug;
                $counter = 1;

                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                $post->slug = $slug;
            }

            $post->description = $validated['description'];
            $post->content = $validated['content'];
            if ($request->has('image')) {
                $post->image = $request->image;
            }

            $post->save();

            if ($request->has('categories')) {
                $post->categories()->sync($request->categories);
            }

            return response()->json($post->load('categories', 'user'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update post: ' . $e->getMessage()], 500);
        } 
    }


    public function publish (Request $request, Post $post) {
        $post->status = 'published';
        $post->published_at = now();
        $post->save();

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->categories()->detach();
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function uploadImage (Request $request, Post $post) {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            try {
                if ($post->image) {
                    $oldPath = $post->image;
                    if (strpos($oldPath, '/storage/') !== false) {
                        $oldPath = substr($oldPath, strpos($oldPath, '/storage/') + 9);
                    }
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file('image')->store('posts', 'public');
                $post->image = $path;
                $post->save();

                return response()->json(['image-path' => asset('storage/' . $path)]);
            } catch (\Exception $e) {
                \Log::error('Image upload failed: ' . $e->getMessage());
                return response()->json(['message' => 'Image upload failed: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'No image file provided'], 400);
    }

}
