<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // percentage | fixed
            $table->decimal('value', 10, 2);
            $table->string('scope'); // all | category | items
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('start_at');
            $table->index('end_at');
            $table->index('type');
            $table->index('scope');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
