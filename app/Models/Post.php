<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'user_id',
        'status',
        'image',
        'published_at',
    ];

    protected $casts = [
        'published-at' => 'datetime'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
}
