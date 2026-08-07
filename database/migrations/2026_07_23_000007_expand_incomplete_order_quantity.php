<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incomplete_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('quantity')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('incomplete_orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('quantity')->default(1)->change();
        });
    }
};
