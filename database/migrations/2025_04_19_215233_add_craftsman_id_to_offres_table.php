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
        // First check if the column already exists
        if (!Schema::hasColumn('offres', 'craftsman_id')) {
            Schema::table('offres', function (Blueprint $table) {
                // Add the column as nullable to avoid constraints on existing data
                $table->unsignedBigInteger('craftsman_id')->nullable()->after('commande_id');
            });
        }
        
        // Try to update existing records with the user's craftsman ID if available
        try {
            $offers = DB::table('offres')->get();
            foreach ($offers as $offer) {
                if (isset($offer->user_id)) {
                    $craftsman = DB::table('craftsmen')
                        ->where('user_id', $offer->user_id)
                        ->first();
                    
                    if ($craftsman) {
                        DB::table('offres')
                            ->where('id', $offer->id)
                            ->update(['craftsman_id' => $craftsman->id]);
                    }
                }
            }
            
            // Check if the foreign key exists already
            $foreignKeys = DB::select(
                "SELECT * FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND TABLE_NAME = 'offres' 
                AND CONSTRAINT_NAME = 'offres_craftsman_id_foreign'"
            );
            
            if (empty($foreignKeys)) {
                // Now add the foreign key constraint
                Schema::table('offres', function (Blueprint $table) {
                    $table->foreign('craftsman_id')->references('id')->on('craftsmen')->onDelete('cascade');
                });
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the migration
            \Log::error('Error updating craftsman_id in offres: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            try {
                $table->dropForeign(['craftsman_id']);
            } catch (\Exception $e) {
                // The foreign key might not exist
            }
            
            if (Schema::hasColumn('offres', 'craftsman_id')) {
                $table->dropColumn('craftsman_id');
            }
        });
    }
};
