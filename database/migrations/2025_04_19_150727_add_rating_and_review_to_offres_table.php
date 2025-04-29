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
        Schema::table('offres', function (Blueprint $table) {
            // إضافة عمود rating للتقييم (نجوم من 1 إلى 5)
            $table->unsignedTinyInteger('rating')->nullable();
            // إضافة عمود review للتعليق على الخدمة
            $table->text('review')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review']);
        });
    }
};
