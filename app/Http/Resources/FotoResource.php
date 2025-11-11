<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $disk = 'public'; // Force using public disk for file storage
        $url = null;
        if ($this->file) {
            try {
                // Since we now store only filename, construct the full path
                $fullPath = 'fotos/' . $this->file;
                $url = Storage::disk($disk)->url($fullPath);
                // Fallback if URL is empty or invalid
                if (empty($url) || $url === $fullPath) {
                    $url = asset('storage/fotos/' . $this->file);
                }
            } catch (\Throwable $e) {
                $url = asset('storage/fotos/' . $this->file);
            }
        }
        return [
            'id' => $this->id,
            'file' => $this->file,
            'file_url' => $url,
            'judul' => $this->judul,
            'galery' => new GaleryResource($this->whenLoaded('galery')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
