<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'judul' => $this->judul,
            'isi' => $this->isi,
            'status' => $this->status,
            'kategori' => new KategoriResource($this->whenLoaded('kategori')),
            'petugas' => [
                'id' => $this->whenLoaded('petugas', function() {
                    return $this->petugas->id;
                }),
                'username' => $this->whenLoaded('petugas', function() {
                    return $this->petugas->username;
                }),
            ],
            'galeries_count' => $this->whenCounted('galeries'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
