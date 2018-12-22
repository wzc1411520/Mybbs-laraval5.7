<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'user' =>new UserResource($this->user),
            'category' =>new CategoryResource($this->category),
            'reply_count' => (int) $this->reply_count,
            'view_count' => (int) $this->view_count,
            'last_reply_user_id' => (int) $this->last_reply_user_id,
            'excerpt' => $this->excerpt,
            'slug' => $this->slug,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
