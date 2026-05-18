<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $categories = [
            ['name' => 'Laravel', 'icon' => 'fa fa-laravel'],
            ['name' => 'Vue', 'icon' => 'fa fa-vuejs'],
            ['name' => 'Tailwind CSS', 'icon' => 'fa fa-css3'],
        ];

        foreach ($categories as $category) {
            Category::create ([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
            ]);
        }

    }
}
