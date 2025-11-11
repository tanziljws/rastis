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
        Schema::table('foto', function (Blueprint $table) {
            $table->dropForeign(['galery_id']);
            $table->foreignId('galery_id')->nullable()->change()->constrained('galery')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foto', function (Blueprint $table) {
            $table->dropForeign(['galery_id']);
            $table->foreignId('galery_id')->nullable(false)->change()->constrained('galery')->onDelete('cascade');
        });
    }
};
