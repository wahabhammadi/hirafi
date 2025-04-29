<?php

/**
 * Script para actualizar las ofertas existentes y convertir los precios de SAR a DZD.
 * 
 * Este script debe ejecutarse después de aplicar la migración que añade el campo 'currency' a la tabla 'offres'.
 * 
 * Uso: php scripts/update_to_dzd_currency.php
 */

// Cargar el entorno de Laravel
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Offre;
use App\Services\CurrencyConversionService;
use Illuminate\Support\Facades\DB;

// Comprobar si existe la columna 'currency'
if (!Schema::hasColumn('offres', 'currency')) {
    echo "Error: La columna 'currency' no existe en la tabla 'offres'. Por favor, ejecuta la migración primero.\n";
    exit(1);
}

echo "Iniciando la actualización de ofertas existentes...\n";

// Obtener el servicio de conversión de moneda
$currencyService = new CurrencyConversionService();

// Contar el número total de ofertas
$totalOffres = Offre::count();
echo "Número total de ofertas: $totalOffres\n";

// Iniciar una transacción de base de datos
DB::beginTransaction();

try {
    // 1. Actualizar todas las ofertas existentes para establecer la moneda a SAR (suponiendo que todos los precios están en SAR)
    $updated1 = DB::table('offres')
        ->whereNull('currency')
        ->update(['currency' => 'SAR']);
    
    echo "Paso 1: $updated1 ofertas actualizadas con moneda SAR.\n";
    
    // 2. Convertir los precios de SAR a DZD para las ofertas con estatus 'pending'
    $offres = Offre::where('currency', 'SAR')
        ->where('status', 'pending')
        ->get();
    
    $convertedCount = 0;
    foreach ($offres as $offre) {
        // Convertir el precio de SAR a DZD
        $priceDZD = $currencyService->convertSARtoDZD($offre->price);
        
        // Actualizar la oferta
        $offre->price = $priceDZD;
        $offre->currency = 'DZD';
        $offre->save();
        
        $convertedCount++;
    }
    
    echo "Paso 2: $convertedCount ofertas pendientes convertidas de SAR a DZD.\n";
    
    // Confirmar los cambios
    DB::commit();
    echo "Actualización completada con éxito.\n";
    
} catch (\Exception $e) {
    // Revertir la transacción en caso de error
    DB::rollBack();
    echo "Error durante la actualización: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nResumen de la actualización:\n";
echo "-------------------------\n";
echo "Ofertas con moneda SAR: " . Offre::where('currency', 'SAR')->count() . "\n";
echo "Ofertas con moneda DZD: " . Offre::where('currency', 'DZD')->count() . "\n";
echo "-------------------------\n";

echo "\nProceso completado.\n"; 