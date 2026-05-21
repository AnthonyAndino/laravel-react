<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request) {
        $user = $request->user();
        $user->avatar = $this->getGravatarUrl($user->email);
        return response()->json($user);
    }

    public function update(Request $request) {
        try {
            $user = $request->user();

            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('users')->ignore($user->id),
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'bio' => 'nullable|string|max:255',
            ]);

            $user->update($validateData);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }

    protected function getGravatarUrl($email) {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    public function updatePassword(Request $request) {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'The provided password does not match your current password'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password update successfully']);

    }
}
