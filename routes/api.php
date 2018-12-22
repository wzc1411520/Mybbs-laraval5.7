<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
        'namespace' => 'App\Http\Controllers\Api',
        'middleware' =>['serializer:array','bindings']
    ],function ($api){
    $api->get('version', function() {
        return response('this is version v1');
    });
    //微信公众号网页授权,获取用户信息
    $api->group(['middleware'=>['wechat.oauth:snsapi_userinfo']],function ($api){
//   Route::group(['middleware'=>'transfer.easywechat.session'],function(){
        $api->get('snsapi_userinfo','UsersController@snsapi_userinfo')->name('api.weChat.userInfo');
//   });
    });
    $api->group([
        //调用频率限制
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ],function ($api){

        //游客访问的接口
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');
        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');
        //获取某个用户的所有话题
        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');
        //获取话题详情
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');

        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.patch');
            $api->put('user', 'UsersController@update')
            ->name('api.user.update');
            // 图片资源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            // 发布话题
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');
            //修改话题
            $api->patch('topics/{topic}','TopicsController@update')
                ->name('api.topics.update');
            $api->delete('topics/{$topic}','TopicsController@destroy')->name('api.topics.destroy');

        });

        //发送验证码
        $api->post('verificationCodes','VerificationCodesController@store')->name('api.verificationCodes.store');
        //用户注册
        $api->post('users','UsersController@store')->name('api.users.store');
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
//*************************************微信公众号*************************************************
        //登录
        $api->get('wechat/authorizations', 'AuthorizationsController@weChatStore')
            ->name('api.authorizations.weChatStore');
        $api->get('wechat/snsapi_userinfo', 'UsersController@snsapi_userinfo')
            ->name('api.user.snsapi_userinfo');


//***********************************小程序************************************************************
        // 登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');// 登录

        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
// 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
        // 小程序登录
        $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');
        // 小程序注册
        $api->post('weapp/users', 'UsersController@weappStore')
            ->name('api.weapp.users.store');

    });

});
$api->version('v2',function ($api){
    $api->get('version', function() {
        return response('this is version v1');
    });
});