<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    //获取未读信息
    public function index()
    {
        $notifications = $this->user()->notifications()->paginate(20);
        return  NotificationResource::collection($notifications);
    }
}
