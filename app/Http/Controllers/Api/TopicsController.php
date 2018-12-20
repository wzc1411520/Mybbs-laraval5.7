<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    //获取话题刘表
    public function index(Request $request,Topic $topic)
    {
        $query = $topic->query();
        if($categoryId = $request->category_id){
            $query->where('category_id', $categoryId);
        }
        // 为了说明 N+1问题，不使用 scopeWithOrder
        switch ($request->order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(10);

        return $this->response->collection($topics);
    }

    //发布话题
    public function store(TopicRequest $request,Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();
        return  new TopicResource($topic);
    }
}
