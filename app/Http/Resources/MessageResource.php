<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'file' => $this->file,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
