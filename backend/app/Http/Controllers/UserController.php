<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 获取所有用户
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // 创建新用户
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users',
            'password' =>'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    // 获取单个用户
    public function show(User $user)
    {
        return response()->json($user);
    }

    // 更新用户
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' =>'sometimes|string|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json($user);
    }

    // 删除用户
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
