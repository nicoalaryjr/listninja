<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $achievements = $user->achievements;
        $lists = $user->lists()->with('items')->latest()->get();
        $isFollowing = auth()->check() ? auth()->user()->following->contains($user->id) : false;
        
        return view('profile.show', compact('user', 'achievements', 'lists', 'isFollowing'));
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:160'],
            'avatar' => ['nullable', 'image', 'max:2048']
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    public function following(User $user)
    {
        $following = $user->following()->paginate(20);
        return view('profile.following', compact('user', 'following'));
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);
        return view('profile.followers', compact('user', 'followers'));
    }

    public function toggleFollow(User $user)
    {
        if (auth()->id() === $user->id) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }

        $isFollowing = auth()->user()->following->contains($user->id);

        if ($isFollowing) {
            auth()->user()->following()->detach($user->id);
            $message = 'Unfollowed successfully';
        } else {
            auth()->user()->following()->attach($user->id);
            $message = 'Followed successfully';
            
            // Send notification for new follower
            $user->notify(new NewFollower(auth()->user()));
            
            // Add points for gaining a follower
            $user->addPoints(5);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'isFollowing' => !$isFollowing
        ]);
    }
}