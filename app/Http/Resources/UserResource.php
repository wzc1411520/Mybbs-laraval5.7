<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [ 
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => storage_url($this->avatar),
            'introduction' => $this->introduction,
            'bound_phone' => $this->phone ? true : false,
            'bound_wechat' => ($this->weixin_unionid || $this->weixin_openid) ? true : false,
            'weapp_openid' => $this->weapp_openid,
            'weixin_session_key' => $this->weixin_session_key,
            'last_actived_at' => $this->last_actived_at->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'role' => RoleResource::collection($this->roles)
            ];
    }

//    public function with($request)
//    {
//        return [
//            'meta'=>[
//                'access_token'=>\Auth::guard('api')->fromUser($this),
//                'token_type' => 'Bearer',
//                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
//            ]
//        ];
//    }
}
