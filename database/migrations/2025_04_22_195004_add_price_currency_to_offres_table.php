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
            // Agregar campo para almacenar la moneda del precio (DZD por defecto)
            $table->string('price_currency')->default('DZD')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn('price_currency');
        });
    }
};
