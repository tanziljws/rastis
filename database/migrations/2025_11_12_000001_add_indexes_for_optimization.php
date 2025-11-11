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
        Schema::table('foto', function (Blueprint $table) {
            $table->index('judul');
            $table->index('kategori_id');
            $table->index('galery_id');
            $table->index('created_at');
            $table->index('batch_id');
        });

        // Add indexes to likes table
        Schema::table('likes', function (Blueprint $table) {
            $table->index('foto_id');
            $table->index('user_id');
        });

        // Add indexes to comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->index('foto_id');
            $table->index('user_id');
            $table->index('created_at');
        });

        // Add indexes to galery table
        Schema::table('galery', function (Blueprint $table) {
            $table->index('status');
            $table->index('post_id');
        });

        // Add indexes to posts table (if exists)
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->index('status');
                $table->index('kategori_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foto', function (Blueprint $table) {
            $table->dropIndex(['judul']);
            $table->dropIndex(['kategori_id']);
            $table->dropIndex(['galery_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['batch_id']);
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

