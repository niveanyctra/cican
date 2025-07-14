<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    //
    public function searchUser(Request $request)
    {
        $keyword = $request->query('query');

        $users = User::where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('username', 'LIKE', "%{$keyword}%")
            ->distinct()->get(['id', 'name', 'username', 'avatar', 'bio']);
        // dd($users);
        return response()->json($users);
    }
}
