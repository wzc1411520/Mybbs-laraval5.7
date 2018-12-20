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
        return parent::toArray($request);
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
