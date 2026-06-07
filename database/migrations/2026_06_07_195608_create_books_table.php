<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->string('slug', 255)->unique();
            $table->longText('description')->nullable();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('file_path')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('pages')->nullable();
            $table->integer('wp_id')->nullable()->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
