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
        Schema::table('craftsmen', function (Blueprint $table) {
            $table->string('craft')->nullable()->after('api_id');
            
            // Modificar la columna rating para permitir valores nulos
            $table->string('rating')->nullable()->default('0')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('craftsmen', function (Blueprint $table) {
            $table->dropColumn('craft');
            
            // Restaurar la columna rating a su estado original
            $table->string('rating')->change();
        });
    }
};
