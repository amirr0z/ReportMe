<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarningResource extends JsonResource
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
            'user' => new UserResource($this->user()),
            'project' => new ProjectResource($this->project()),
            'description' => $this->description,
            'score' => $this->score,
            'file' => $this->file,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
