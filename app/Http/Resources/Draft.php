<?php

namespace App\Http\Resources;

use App\Http\Resources\Note as NoteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Draft extends JsonResource
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
            'alias' => $this->alias,
            'short_description' => $this->short_description,
            'notes' =>NoteResource::collection($this->notes),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
