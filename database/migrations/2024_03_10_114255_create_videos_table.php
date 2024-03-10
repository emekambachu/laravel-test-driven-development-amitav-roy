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
        Schema::create('videos', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('url');
            $table->longText('description');
            $table->enum('type', ['youtube', 'vimeo'])->default('youtube');
            $table->boolean('is_published')->default(0);
            $table->integer('like_count')->unsigned()->default(0);
            $table->integer('abuse_count')->unsigned()->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
