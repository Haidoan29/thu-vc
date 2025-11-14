<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // dùng chung view
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            Session::put('user_id', $user->_id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);
            if($user->role == 'admin'){
                return redirect('/admin/products');
            } else {
                return redirect('/');
            }
        }

        return back()->with('error','Email or password is incorrect');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.login');
    }
}
