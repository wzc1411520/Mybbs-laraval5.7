<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

//        return $this->response->array([$verifyData['code'],$request->verification_code,$verifyData]);
        if (!hash_equals((string)$verifyData['code'], $request->verification_code)){
            return $this->response->errorUnauthorized('验证码错误');
        }
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);
        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->array($user->toArray())->setStatusCode(201);
    }
}
