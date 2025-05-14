<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    //发送短信验证码
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^1[3-9]\d{9}$/', 'max:11'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $phone = $request->input('phone');
        $code = rand(100000, 999999);

        // 存入缓存（5分钟）
        Cache::put("code_{$phone}", $code, now()->addMinutes(5));

        // 模拟发送短信（可替换为真实服务）
        Log::info("验证码发送给 {$phone}: {$code}");

        return response()->json([
            'success' => true,
            'message' => '验证码发送成功',
        ]);
    }

    // 生成图片验证码（返回图片URL和缓存key）
    public function generateCaptcha()
    {
        $key = Str::random(32); // 生成一个随机 key 用于缓存标识
        // 生成验证码并获取图片内容
        $builder = Captcha::create('default', true);
        // 获取验证码文本（从 session 中）
        $captchaText = $builder['key'];

        // 存入缓存
        Cache::put("captcha_{$key}", $captchaText, now()->addMinutes(5));

        return response()->json([
            'key' => $key,
            'image' => $builder['img']
        ]);
    }


    // 注册
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^1[3-9]\d{9}$/', 'unique:users,phone', 'max:11'],
            'code' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $phone = $request->input('phone');
        $code = $request->input('code');

        // 校验验证码
        $storedCode = Cache::get("code_{$phone}");
        if (!$storedCode || $code != $storedCode) {
            return response()->json([
                'success' => false,
                'message' => '验证码错误或已过期',
            ], 400);
        }

        // 创建用户
        $user = User::create([
            'phone' => $phone,
            'password' => bcrypt($request->input('password')),
        ]);

        // 清除验证码
        Cache::forget("register_code_{$phone}");

        return response()->json([
            'success' => true,
            'message' => '注册成功',
            'user' => $user
        ]);
    }

    // 登录
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'regex:/^1[3-9]\d{9}$/', 'max:11'],
            'password' => ['required', 'min:6', 'max:20', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,20}$/'],
            'captcha_key' => ['required', 'string', 'size:32'],
            'captcha_code' => ['required', 'string', 'size:4'],
        ]);
        $phone = $request->input('phone');
        $password = $request->input('password');
        $captchaKey = $request->input('captcha_key');
        $captchaCode = $request->input('captcha_code');

        // 校验验证码
        $cachedCode = Cache::get("captcha_{$captchaKey}");
        if (!$cachedCode ||  !Hash::check(strtolower($captchaCode),$cachedCode)) {
            return response()->json([
                'success' => false,
                'message' => '验证码错误',
            ], 400);
        }

        $user = User::where('phone', $phone)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => '手机号或密码错误',
            ], 401);
        }


        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => '登录成功',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // 重置密码
    public function resetPassword(Request $request)
    {
        $phone = $request->input('phone');
        $code = $request->input('code');
        $password = $request->input('password');

        // 校验验证码
        $cachedCode = Cache::get("code_{$phone}");
        if (!$cachedCode || $cachedCode != $code) {
            return response()->json([
                'success' => false,
                'message' => '验证码错误或已过期',
            ], 422);
        }

        // 更新用户密码
        $user = \App\Models\User::where('phone', $phone)->first();
        $user->password = bcrypt($password);
        $user->save();

        // 清除验证码缓存
        Cache::forget("reset_code_{$phone}");

        return response()->json([
            'success' => true,
            'message' => '密码重置成功',
        ]);
    }

    // 退出登录
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => '退出成功']);
    }
}
