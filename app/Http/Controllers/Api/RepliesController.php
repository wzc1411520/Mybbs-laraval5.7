<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RepliesRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    //f发表评论
    public function store(RepliesRequest $request,Topic $topic,Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();
        return  new ReplyResource($reply);
    }

    //删除
    public function destroy(Topic $topic,Reply $reply)
    {
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }
    
    //话题的回复列表
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->paginate(20);
        return  ReplyResource::collection($replies);
    }

    //某个用户的回复列表
    public function userIndex(User $user)
    {
        $replies = $user->replies()->paginate(10);
        return ReplyResource::collection($replies);
    }
}
