<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SomeController extends Controller
{
    public function protectedMethod(Request $request)
    {
        // 这里处理需要登录才能访问的业务逻辑
        return response()->json(['message' => '这是一个需要登录才能访问的API']);
    }
}
