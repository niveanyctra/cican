<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        // $photos = Photo::all()->sortByDesc('created_at');
        // $users = User::all()->sortByDesc('created_at');
        return view('index', compact('photos', 'users'));
    }
}
