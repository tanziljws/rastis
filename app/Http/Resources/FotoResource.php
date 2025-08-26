<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file' => $this->file,
            'file_url' => $this->file ? asset('storage/' . $this->file) : null,
            'judul' => $this->judul,
            'galery' => new GaleryResource($this->whenLoaded('galery')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
