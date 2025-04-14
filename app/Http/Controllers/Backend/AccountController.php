<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }
    public function registerProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'address' => 'required',
            'password' => 'required',
        ]);
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'address' => $request->address,
            'password' => bcrypt($request->password),
        ]);
        return redirect('login');
    }

    public function show(string $username)
    {
        $user = User::where('username', $username)->first();
        // $albums = Album::where('user_id', $user->id)->get();
        // $photos = Photo::where('user_id', $user->id)->get();
        return view('pages.user.show', compact('user', 'albums', 'photos'));
    }

    public function edit(string $id)
    {
        $user = User::find($id);
        return view('pages.user.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'required',
            'password' => 'nullable',
        ]);
        if($request->password)
        {
            $user->password = bcrypt($request->password);
        }
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();
        return redirect()->route('user.show', $user->username);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('home');
    }
}
