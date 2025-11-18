<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // dùng chung view
    }
    public function userLogin()
    {
        return view('user.auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user_id', $user->_id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);
            if ($user->role == 'admin') {
                return redirect('/admin/products');
            } else {
                return redirect('/');
            }
        }

        return back()->with('error', 'Email or password is incorrect');
    }

    public function logout()
    {
        $role = Session::get('user_role'); // lấy role trước khi xoá session
        Session::flush(); // xoá tất cả session
       
        if ($role === 'admin') {
            // dd('hahah');
            return redirect()->route('admin.login');
        }
        return redirect()->route('user.login');
    }


    public function showRegisterForm()
    {
        return view('user.auth.register');
    }
    public function register(Request $request)
    {
        // Validate cơ bản
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Kiểm tra email đã tồn tại chưa
        $exists = User::where('email', $request->email)->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Email này đã được đăng ký!');
        }

        // Tạo user mới
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // mutator tự hash
            'role' => 'user', // mặc định
        ]);

        // Chuyển về login
        return redirect()->route('user.login')->with('success', 'Đăng ký thành công. Vui lòng đăng nhập!');
    }
}
