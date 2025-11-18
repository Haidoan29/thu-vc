<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // số item mỗi trang

        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('admin.users.index', compact('users', 'perPage'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // Không cần mã hóa nữa nếu đã có mutator trong Model
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return redirect()->back()->with('error', 'User not found');

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
    public function profile()
    {
        $userId = Session::get('user_id'); // lấy id user từ session
        $user = User::find($userId);
        return view('user.profile.index', compact('user'));
    }
    public function updateAccount(Request $request)
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Kiểm tra email trùng
        $exists = User::where('email', $request->email)
            ->where('_id', '!=', $userId)
            ->first();
        if ($exists) {
            return back()->with('error', 'Email này đã được sử dụng bởi người khác!');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = $request->password; 
        }

        $user->save();

        // CẬP NHẬT SESSION TÊN MỚI
        Session::put('user_name', $user->name);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}
