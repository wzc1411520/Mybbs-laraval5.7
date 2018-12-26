<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //微信授权->获取用户信息
    public function snsapi_userinfo(User $user)
    {
        $weChat = \EasyWeChat::officialAccount();
        //获取微信用户信息
        $oauthUser = $weChat->oauth->user();
        //判断用户是否存在
        $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;
        $userInfo = $user->where(function($query)use($oauthUser){
            if($oauthUser->offsetExists('unionid')){
                $query->where('weixin_unionid',$oauthUser->offsetGet('unionid'));
            }else{
                $query->where('weixin_openid',$oauthUser->getId());
            }
        })->first();

//        $key = 'wechatInfoCode_'.str_random(15);
//        $expiredAt = now()->addMinutes(10);

        if(!$userInfo){

            //创建新用户
            $userInfo = User::create([
                'name' => $oauthUser->getNickname(),
                'avatar' => $oauthUser->getAvatar(),
                'weixin_openid' => $oauthUser->getId(),
                'weixin_unionid' => $unionid,
            ]);


            //放入缓存,跳转到绑定用户页面,进行绑定
//            // 缓存验证码 10分钟过期。
//            \Cache::put($key, ['user_info' => $res], $expiredAt);
//            return $this->response->array([
//                'key' => $key,
//                'type' => 1,
//                'expired_at' => $expiredAt->toDateTimeString(),
//            ])->setStatusCode(200);
        }
        $token = Auth::guard('api')->fromUser($userInfo);
        return $this->respondWithToken($token)->setStatusCode(201);

    }
    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }
    //绑定用户
    public function bindUserByWeChat(UserRequest $request)
    {

    }


    //用户注册
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

        return (new UserResource($this->user()))->additional(['meta' => [
            'access_token' => \Auth::guard('api')->fromUser($this->user()),
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]]);
//        return $this->response->array($user->toArray())->setStatusCode(201);
    }
    //获取个人资料
    public function me()
    {
        return (new UserResource($this->user()))->additional(['meta' => [
            'access_token' => \Auth::guard('api')->fromUser($this->user()),
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]]);
//        return $this->response->array(new UserResource($this->user()))->setStatusCode(201);
    }
    //编辑个人信息
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return $this->response->array($user->toArray());
    }

    //小程序**********************************************************************
    //用户注册
    public function weappStore(UserRequest $request)
    {
        //判断缓存中是否有数据
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        //如果存在,判断是否相等
        if(!hash_equals((string)$verifyData['code'],$request->verification_code)){
            return $this->reponse->errorUnauthorized('验证码错误');
        }
        //获取openid和sesssion_key
        $miniprogram = \EasyWeChat::miniProgram();
        $data = $miniprogram->auth->session($request->code);
        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code 不正确');
        }

        //获取用户信息
        $userInfo = \App\Models\User::where('weapp_openid',$data['openid'])->first();
        if($userInfo){
            return $this->response->errorForbidden('微信已绑定其他用户，请直接登录');
        }

        // 创建用户
        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'weapp_openid' => $data['openid'],
            'weixin_session_key' => $data['session_key'],
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        // meta 中返回 Token 信息
        return $this->response->array([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'weapp_openid' => $data['openid'],
            'weixin_session_key' => $data['session_key'],
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    //获取用户详情
    public function show(User $user)
    {
        return new UserResource($user);
    }

    //获取活跃用户
    public function activedIndex(User $user)
    {
        $cativiedUser = $user->getActiveUsers();
        return UserResource::collection($cativiedUser);
    }
}
