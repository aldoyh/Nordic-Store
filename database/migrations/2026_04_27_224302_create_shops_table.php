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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('instagram_username')->unique();
            $table->string('shop_name')->nullable();
            $table->text('shop_description')->nullable();
            $table->enum('instagram_fetch_status', ['pending', 'fetching', 'completed', 'failed'])->default('pending');
            $table->timestamp('instagram_last_synced_at')->nullable();
            $table->integer('total_images_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('user_id');
            $table->index('instagram_fetch_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
