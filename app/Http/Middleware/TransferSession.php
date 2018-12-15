<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class TransferSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $wechat_user = session('wechat.oauth_user.default');
        dd($wechat_user);
        if(!Auth::check()){
            $wechat_info = $wechat_user->original;
            $user = User::where('wexin_openid',$wechat_info['openid'])->first();
            if(is_null($user)){
                $wechat_info = $wechat_user->original ;
                $user = new  User();
                $user->weixin_openid = $wechat_info['openid'];
                $user->weixin_unionid = $wechat_info['unionid'];
                $user->avatar = $wechat_info['headimgurl'];
                $user->save();
                Auth::login($user);
            }else {
                Auth::login($user);
            }
        }
        return $next($request);
    }
}
