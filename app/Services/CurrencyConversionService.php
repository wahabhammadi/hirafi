<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyConversionService
{
    /**
     * القيمة الثابتة لتحويل الريال السعودي إلى الدينار الجزائري
     * قد تحتاج للتحديث حسب أسعار الصرف الحالية
     */
    const SAR_TO_DZD_RATE = 36.75; // سعر التحويل (قيمة تقريبية، يجب تحديثها)
    const DZD_TO_SAR_RATE = 0.0272; // عكس سعر التحويل (1 / 36.75)
    
    /**
     * العملة الافتراضية للنظام - تم تعيينها إلى الدينار الجزائري
     */
    const DEFAULT_CURRENCY = 'DZD';
    
    /**
     * تحويل المبلغ من الريال السعودي إلى الدينار الجزائري
     *
     * @param float $amount المبلغ بالريال السعودي
     * @return float المبلغ بالدينار الجزائري
     */
    public function convertSARtoDZD($amount)
    {
        return round($amount * self::SAR_TO_DZD_RATE, 2);
    }
    
    /**
     * تحويل المبلغ من الدينار الجزائري إلى الريال السعودي
     *
     * @param float $amount المبلغ بالدينار الجزائري
     * @return float المبلغ بالريال السعودي
     */
    public function convertDZDtoSAR($amount)
    {
        return round($amount * self::DZD_TO_SAR_RATE, 2);
    }
    
    /**
     * الحصول على سعر الصرف الحالي
     *
     * @param string $from العملة المصدر
     * @param string $to العملة الهدف
     * @return float سعر الصرف الحالي
     */
    public function getExchangeRate($from = 'DZD', $to = 'SAR')
    {
        if ($from === 'DZD' && $to === 'SAR') {
            return self::DZD_TO_SAR_RATE;
        } elseif ($from === 'SAR' && $to === 'DZD') {
            return self::SAR_TO_DZD_RATE;
        } else {
            // يمكن إضافة أزواج عملات أخرى هنا في المستقبل
            return 1;
        }
    }
    
    /**
     * الحصول على سعر الصرف الحالي من API خارجي
     * (استخدم في حال كنت تريد أسعار تحويل حقيقية بدلاً من القيم الثابتة)
     *
     * @return float سعر الصرف الحالي
     */
    public function getLiveExchangeRate()
    {
        // استخدام الكاش لتقليل عدد الطلبات للـ API
        return Cache::remember('sar_to_dzd_rate', 3600, function () {
            try {
                // يمكنك استخدام API مثل:
                // - https://exchangeratesapi.io/
                // - https://openexchangerates.org/
                // - https://currencylayer.com/
                
                // مثال باستخدام exchangeratesapi.io (تحتاج لإضافة مفتاح API في ملف .env)
                // $response = Http::get('https://api.exchangeratesapi.io/latest', [
                //     'base' => 'SAR',
                //     'symbols' => 'DZD',
                //     'access_key' => env('EXCHANGE_RATE_API_KEY')
                // ]);
                
                // if ($response->successful()) {
                //     return $response->json()['rates']['DZD'];
                // }
                
                // في حالة فشل API استخدم القيمة الثابتة كاحتياطي
                return self::SAR_TO_DZD_RATE;
            } catch (\Exception $e) {
                report($e);
                return self::SAR_TO_DZD_RATE;
            }
        });
    }
    
    /**
     * الحصول على رمز العملة الافتراضية للنظام
     *
     * @return string رمز العملة
     */
    public function getDefaultCurrencySymbol()
    {
        return self::DEFAULT_CURRENCY === 'DZD' ? 'دج' : 'ر.س';
    }
    
    /**
     * الحصول على رمز العملة بناءً على الرمز المختصر للعملة
     *
     * @param string $currency رمز العملة المختصر
     * @return string رمز العملة
     */
    public function getCurrencySymbol($currency = null)
    {
        $currency = $currency ?: self::DEFAULT_CURRENCY;
        
        switch ($currency) {
            case 'DZD':
                return 'دج';
            case 'SAR':
                return 'ر.س';
            default:
                return 'دج';
        }
    }
} 