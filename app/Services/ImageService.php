<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Process and compress image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param int $maxWidth
     * @param int $quality
     * @return array ['original' => string, 'thumbnail' => string]
     */
    public static function processImage($file, $folder = 'fotos', $maxWidth = 1920, $quality = 85)
    {
        // Set time limit for image processing
        set_time_limit(60); // 1 minute for processing
        ini_set('memory_limit', '256M');
        
        $originalPath = $file->store($folder, 'public');
        $fullPath = storage_path('app/public/' . $originalPath);
        
        // Process image
        try {
            // Check file size before processing
            $fileSize = filesize($fullPath);
            if ($fileSize > 10 * 1024 * 1024) { // 10MB
                \Log::warning('Large file detected, skipping processing', ['size' => $fileSize]);
                return [
                    'original' => $originalPath,
                    'thumbnail' => null
                ];
            }
            
            $image = Image::read($fullPath);
            
            // Resize if larger than maxWidth
            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }
            
            // Convert PNG to JPEG if no transparency (to save space)
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            
            if (($extension === 'png' || $mimeType === 'image/png') && !$image->hasAlphaChannel()) {
                $newPath = str_replace('.png', '.jpg', $fullPath);
                $originalPath = str_replace('.png', '.jpg', $originalPath);
                $image->toJpeg($quality)->save($newPath);
                // Delete original PNG
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                $fullPath = $newPath;
            } else {
                // Save with quality
                if ($extension === 'jpg' || $extension === 'jpeg' || $mimeType === 'image/jpeg') {
                    $image->toJpeg($quality)->save($fullPath);
                } else {
                    $image->save($fullPath);
                }
            }
        } catch (\Exception $e) {
            // If image processing fails, just use the original file
            // Log error but don't break the upload
            \Log::warning('Image processing failed: ' . $e->getMessage());
        }
        
        // Generate thumbnail
        $thumbnailPath = self::generateThumbnail($fullPath, $folder, 400);
        
        return [
            'original' => $originalPath,
            'thumbnail' => $thumbnailPath
        ];
    }
    
    /**
     * Generate thumbnail from image
     * 
     * @param string $imagePath
     * @param string $folder
     * @param int $size
     * @return string
     */
    public static function generateThumbnail($imagePath, $folder = 'fotos', $size = 400)
    {
        try {
            // Check if file exists and is readable
            if (!file_exists($imagePath) || !is_readable($imagePath)) {
                \Log::warning('Thumbnail generation skipped: file not accessible', ['path' => $imagePath]);
                return null;
            }
            
            // Skip thumbnail for very large files
            $fileSize = filesize($imagePath);
            if ($fileSize > 10 * 1024 * 1024) { // 10MB
                \Log::info('Thumbnail generation skipped for large file', ['size' => $fileSize]);
                return null;
            }
            
            $image = Image::read($imagePath);
            
            // Resize to square thumbnail (cover crop)
            $image->cover($size, $size);
            
            // Get thumbnail path
            $pathInfo = pathinfo($imagePath);
            $thumbnailDir = storage_path('app/public/' . $folder . '/thumbnails');
            
            // Create thumbnails directory if not exists
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }
            
            // Use jpg for thumbnails to save space
            $thumbnailExtension = 'jpg';
            $thumbnailPath = $thumbnailDir . '/' . $pathInfo['filename'] . '_thumb.' . $thumbnailExtension;
            
            // Save thumbnail as JPEG
            $image->toJpeg(85)->save($thumbnailPath);
            
            return $folder . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $thumbnailExtension;
        } catch (\Exception $e) {
            \Log::warning('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete image and thumbnail
     * 
     * @param string $path
     * @return bool
     */
    public static function deleteImage($path)
    {
        // Delete original
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        
        // Delete thumbnail
        $pathInfo = pathinfo($path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
        
        return true;
    }
}

