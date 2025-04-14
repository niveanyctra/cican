<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('username', $request->username)->first();
        if(!$user)
        {
            return redirect('login');
        }
        if(Hash::check($request->password, $user->password))
        {
            Auth::loginUsingId($user->id);
            return redirect()->route('home');
        }
        return redirect('login');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('home');
    }
}
