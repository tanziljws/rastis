<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Foto;

class FixPhotoPermissions extends Command
{
    protected $signature = 'fotos:fix-permissions';
    protected $description = 'Fix file permissions for all uploaded photos';

    public function handle()
    {
        $this->info('🔧 Fixing photo file permissions...');
        
        $fotos = Foto::all();
        $fixed = 0;
        $missing = 0;
        
        foreach ($fotos as $foto) {
            // Check original file
            $filePath = $foto->file;
            if (str_contains($filePath, '/')) {
                // Full path (e.g., "fotos/filename.jpg")
                $fullPath = $filePath;
            } else {
                // Just filename, assume fotos folder
                $fullPath = 'fotos/' . $filePath;
            }
            
            if (Storage::disk('public')->exists($fullPath)) {
                $realPath = Storage::disk('public')->path($fullPath);
                chmod($realPath, 0644);
                $fixed++;
                $this->line("✅ Fixed: {$fullPath}");
            } else {
                $missing++;
                $this->warn("⚠️  Missing: {$fullPath}");
            }
            
            // Check thumbnail if exists
            if ($foto->thumbnail) {
                $thumbPath = $foto->thumbnail;
                if (Storage::disk('public')->exists($thumbPath)) {
                    $realPath = Storage::disk('public')->path($thumbPath);
                    chmod($realPath, 0644);
                    $this->line("✅ Fixed thumbnail: {$thumbPath}");
                }
            }
        }
        
        // Fix all files in fotos directory
        $this->info('🔧 Fixing all files in storage/app/public/fotos...');
        $fotosPath = storage_path('app/public/fotos');
        if (is_dir($fotosPath)) {
            $files = glob($fotosPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    chmod($file, 0644);
                }
            }
        }
        
        // Fix thumbnails directory
        $thumbnailsPath = storage_path('app/public/fotos/thumbnails');
        if (is_dir($thumbnailsPath)) {
            $files = glob($thumbnailsPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    chmod($file, 0644);
                }
            }
        }
        
        $this->info("✅ Fixed permissions for {$fixed} files");
        if ($missing > 0) {
            $this->warn("⚠️  {$missing} files are missing from storage");
        }
        
        return 0;
    }
}

