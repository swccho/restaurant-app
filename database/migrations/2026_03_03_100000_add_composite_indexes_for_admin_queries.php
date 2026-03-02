<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->index(['category_id', 'is_available']);
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->index(['is_active', 'start_at', 'end_at']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex(['category_id', 'is_available']);
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'start_at', 'end_at']);
        });
    }
};
