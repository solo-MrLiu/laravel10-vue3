<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Facades\Captcha;

class AuthController extends Controller
{
    // 生成验证码（返回图片URL和缓存key）
    public function generateCaptcha()
    {
        $key = Str::random(32);
        $captcha = Captcha::create('default', true);
        Cache::put("captcha_{$key}", $captcha['text'], 300); // 5分钟有效期
        return response()->json([
            'key' => $key,
            'image' => $captcha['img']
        ]);
    }

    // 注册
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
//            'captcha_key' => 'required|string',
//            'captcha_code' => 'required|string'
        ]);

//        // 验证验证码
//        $cachedCode = Cache::get("captcha_{$request->captcha_key}");
//        if (!$cachedCode || strtolower($request->captcha_code) !== strtolower($cachedCode)) {
//            return response()->json(['message' => '验证码错误'], 422);
//        }

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => '注册成功'], 200);
    }

    // 登录
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
//            'captcha_key' => 'required|string',
//            'captcha_code' => 'required|string'
        ]);

//        // 验证验证码
//        $cachedCode = Cache::get("captcha_{$request->captcha_key}");
//        if (!$cachedCode || strtolower($request->captcha_code) !== strtolower($cachedCode)) {
//            return response()->json(['message' => '验证码错误'], 422);
//        }

        $user = User::where('name', $request->username)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => '用户名或密码错误'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
//            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // 忘记密码（发送邮件）
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string'
        ]);

        // 验证验证码
        $cachedCode = Cache::get("captcha_{$request->captcha_key}");
        if (!$cachedCode || strtolower($request->captcha_code) !== strtolower($cachedCode)) {
            return response()->json(['message' => '验证码错误'], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => '邮箱未注册'], 404);
        }

        $resetToken = Str::random(64);
        Cache::put("password_reset_{$user->email}", $resetToken, 3600); // 1小时有效期

        // 发送重置邮件（示例）
        Mail::raw("重置密码链接：http://your-frontend.com/reset-password?email={$user->email}&token={$resetToken}", function ($message) use ($user) {
            $message->to($user->email)->subject('重置密码');
        });

        return response()->json(['message' => '重置邮件已发送']);
    }

    // 重置密码
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string'
        ]);

        // 验证验证码
        $cachedCode = Cache::get("captcha_{$request->captcha_key}");
        if (!$cachedCode || strtolower($request->captcha_code) !== strtolower($cachedCode)) {
            return response()->json(['message' => '验证码错误'], 422);
        }

        $storedToken = Cache::get("password_reset_{$request->email}");
        if (!$storedToken || $storedToken !== $request->token) {
            return response()->json(['message' => '无效或过期的重置链接'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        Cache::forget("password_reset_{$request->email}");

        return response()->json(['message' => '密码重置成功']);
    }

    // 退出登录
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => '退出成功']);
    }
}
