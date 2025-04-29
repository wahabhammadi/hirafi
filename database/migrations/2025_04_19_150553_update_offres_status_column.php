<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعديل عمود status في جدول offres لإضافة قيمة completed
        DB::statement("ALTER TABLE offres MODIFY status ENUM('pending', 'accepted', 'rejected', 'completed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة عمود status إلى القيم الأصلية
        DB::statement("ALTER TABLE offres MODIFY status ENUM('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
