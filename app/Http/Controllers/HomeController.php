<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function snsapi_userinfo()
    {
        $weChat = \EasyWeChat::officialAccount();
        $response = $weChat->oauth->user()->getOriginal();
        return $response;
//        $wechat_user = session('wechat.oauth_user.default');
//        dd($wechat_user->getId());
    }

    public function snsapi_base()
    {
        $wechat_user = session('wechat.oauth_user.default');
        dd($wechat_user);
    }
}
