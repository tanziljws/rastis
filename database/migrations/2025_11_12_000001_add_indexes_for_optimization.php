<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to foto table
        // Note: batch_id index already exists from migration 2025_11_11_153143_add_batch_id_to_foto_table.php
        Schema::table('foto', function (Blueprint $table) {
            try {
                $table->index('judul');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('kategori_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('galery_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('created_at');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            // batch_id index already created in previous migration, skip it
        });

        // Add indexes to likes table
        Schema::table('likes', function (Blueprint $table) {
            try {
                $table->index('foto_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('user_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        });

        // Add indexes to comments table
        Schema::table('comments', function (Blueprint $table) {
            try {
                $table->index('foto_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('user_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('created_at');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        });

        // Add indexes to galery table
        Schema::table('galery', function (Blueprint $table) {
            try {
                $table->index('status');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
            try {
                $table->index('post_id');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        });

        // Add indexes to posts table (if exists)
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                try {
                    $table->index('status');
                } catch (\Exception $e) {
                    // Index already exists, skip
                }
                try {
                    $table->index('kategori_id');
                } catch (\Exception $e) {
                    // Index already exists, skip
                }
                try {
                    $table->index('created_at');
                } catch (\Exception $e) {
                    // Index already exists, skip
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foto', function (Blueprint $table) {
            try {
                $table->dropIndex(['judul']);
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
            try {
                $table->dropIndex(['kategori_id']);
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
            try {
                $table->dropIndex(['galery_id']);
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
            try {
                $table->dropIndex(['created_at']);
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
            // batch_id index is managed by its own migration, don't drop it here
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['foto_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['foto_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('galery', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['post_id']);
        });

        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropIndex(['status']);
                $table->dropIndex(['kategori_id']);
                $table->dropIndex(['created_at']);
            });
        }
    }
};

