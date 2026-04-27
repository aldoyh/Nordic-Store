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
        Schema::create('instagram_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'success', 'error', 'partial'])->default('pending');
            $table->text('message')->nullable();
            $table->integer('images_fetched_count')->default(0);
            $table->integer('images_failed_count')->default(0);
            $table->timestamps();
            $table->index('shop_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_sync_logs');
    }
};
