<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'content' => $this->content,
            'file' => $this->file,
            'user' => new UserResource($this->user),
            'message' => new MessageResource($this->message),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'seen_at' => $this->seen_at,
        ];
    }
}
