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
        // Modificar la columna address en la tabla users
        Schema::table('users', function (Blueprint $table) {
            // Primero crear una columna temporal
            $table->string('province_code', 5)->nullable()->after('address');
        });

        // Modificar la columna address en la tabla commandes
        Schema::table('commandes', function (Blueprint $table) {
            // Primero crear una columna temporal
            $table->string('province_code', 5)->nullable()->after('address');
        });

        // Ahora eliminamos las columnas antiguas y renombramos las nuevas
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('province_code', 'address');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->renameColumn('province_code', 'address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Modificar la columna address en la tabla users
        Schema::table('users', function (Blueprint $table) {
            // Primero crear una columna temporal
            $table->text('old_address')->nullable()->after('address');
        });

        // Modificar la columna address en la tabla commandes
        Schema::table('commandes', function (Blueprint $table) {
            // Primero crear una columna temporal
            $table->text('old_address')->nullable()->after('address');
        });

        // Ahora eliminamos las columnas antiguas y renombramos las nuevas
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('old_address', 'address');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('address');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->renameColumn('old_address', 'address');
        });
    }
}; 