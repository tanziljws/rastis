<?php

namespace App\Console\Commands;

use App\Models\Foto;
use App\Services\ImageService;
use Illuminate\Console\Command;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fotos:generate-thumbnails {--force : Force regenerate all thumbnails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate thumbnails for all photos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        $query = Foto::query();
        if (!$force) {
            $query->whereNull('thumbnail');
        }
        
        $fotos = $query->get();
        $total = $fotos->count();
        
        if ($total === 0) {
            $this->info('No photos need thumbnail generation.');
            return 0;
        }
        
        $this->info("Generating thumbnails for {$total} photos...");
        
        $bar = $this->output->createProgressBar($total);
        $bar->start();
        
        $success = 0;
        $failed = 0;
        
        foreach ($fotos as $foto) {
            try {
                $fullPath = storage_path('app/public/' . $foto->file);
                
                if (!file_exists($fullPath)) {
                    $this->newLine();
                    $this->warn("File not found: {$foto->file}");
                    $failed++;
                    $bar->advance();
                    continue;
                }
                
                $thumbnailPath = ImageService::generateThumbnail($fullPath, 'fotos', 400);
                $foto->update(['thumbnail' => $thumbnailPath]);
                $success++;
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error generating thumbnail for photo {$foto->id}: " . $e->getMessage());
                $failed++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Generated {$success} thumbnails successfully.");
        
        if ($failed > 0) {
            $this->warn("⚠️  Failed to generate {$failed} thumbnails.");
        }
        
        return 0;
    }
}

