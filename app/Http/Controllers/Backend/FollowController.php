<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id !== $user->id && !$authUser->followings->contains($user->id)) {
            $authUser->followings()->attach($user->id);
        }

        return back();
    }

    public function unfollow(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id !== $user->id && $authUser->followings->contains($user->id)) {
            $authUser->followings()->detach($user->id);
        }

        return back();
    }

    public function toggle(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'Tidak bisa follow diri sendiri'], 403);
        }

        if ($authUser->followings()->where('following_id', $user->id)->exists()) {
            $authUser->followings()->detach($user->id);
            return response()->json(['status' => 'unfollowed']);
        } else {
            $authUser->followings()->attach($user->id);
            return response()->json(['status' => 'followed']);
        }
    }
}
