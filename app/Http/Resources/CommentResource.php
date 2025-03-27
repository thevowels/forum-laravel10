<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class CommentResource extends JsonResource
{
    private bool $withLikePermission = false;

    public function withLikePermission():self
    {
        $this->withLikePermission = true;
        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'post' => $this->whenLoaded('post', fn () =>  PostResource::make($this->post)),
            'body' => $this->body,
            'html' => $this->html,
            'likes_count' => Number::abbreviate($this->likes_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can' => [
                'delete' => $request->user()?->can('delete', $this->resource),
                'update' => $request->user()?->can('update', $this->resource),
                'like' => $this->when($this->withLikePermission, fn () => $request->user()?->can('create', [Like::class, $this->resource])) ,

            ]
        ];
    }
}
