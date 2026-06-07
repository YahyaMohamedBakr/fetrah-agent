<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('benefits')->nullable();
            $table->text('target_audience')->nullable();
            $table->text('requirements')->nullable();
            $table->string('price_type')->default('free');
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('thumbnail')->nullable();
            $table->string('level')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('wp_id')->nullable()->unique();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('total_lessons')->default(0);
            $table->unsignedInteger('total_students')->default(0);
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
