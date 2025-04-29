<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CurrencySettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Mostrar la página de configuración de la moneda.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $currencyService = new CurrencyConversionService();
        $sarToDzdRate = CurrencyConversionService::SAR_TO_DZD_RATE;
        $dzdToSarRate = CurrencyConversionService::DZD_TO_SAR_RATE;
        $defaultCurrency = CurrencyConversionService::DEFAULT_CURRENCY;
        
        return view('admin.currency.index', compact(
            'sarToDzdRate',
            'dzdToSarRate',
            'defaultCurrency'
        ));
    }

    /**
     * Actualizar la tasa de conversión en el caché.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRate(Request $request)
    {
        $request->validate([
            'sar_to_dzd_rate' => 'required|numeric|min:0.01',
        ]);

        // Actualizamos la tasa de conversión en el caché
        Cache::put('sar_to_dzd_rate', $request->sar_to_dzd_rate, 86400); // 24 horas
        
        // También calculamos y almacenamos la tasa inversa
        $dzdToSarRate = 1 / $request->sar_to_dzd_rate;
        Cache::put('dzd_to_sar_rate', $dzdToSarRate, 86400); // 24 horas

        return redirect()->route('admin.currency.index')
            ->with('success', 'تم تحديث سعر الصرف بنجاح. سيتم استخدام هذه القيمة حتى إعادة تشغيل التطبيق أو مسح ذاكرة التخزين المؤقت.');
    }

    /**
     * Ejecutar el script de actualización de moneda.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runConversionScript()
    {
        try {
            // Comprobar si existe el script
            $scriptPath = base_path('scripts/update_to_dzd_currency.php');
            
            if (!file_exists($scriptPath)) {
                return redirect()->route('admin.currency.index')
                    ->with('error', 'ملف البرنامج النصي للتحويل غير موجود. يرجى التحقق من وجود الملف في المسار الصحيح.');
            }
            
            // Ejecutar el script
            $output = shell_exec('php ' . $scriptPath . ' 2>&1');
            
            return redirect()->route('admin.currency.index')
                ->with('success', 'تم تنفيذ البرنامج النصي بنجاح.')
                ->with('output', $output);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.currency.index')
                ->with('error', 'حدث خطأ أثناء تنفيذ البرنامج النصي: ' . $e->getMessage());
        }
    }
} 