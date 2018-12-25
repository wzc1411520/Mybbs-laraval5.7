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
    
    //通知统计
    public function stats()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }

    //标记信息已读
    public function read()
    {
        $this->user()->markAsRead();
        return $this->response->noContent();
    }
}
