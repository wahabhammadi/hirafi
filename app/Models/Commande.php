<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CurrencyConversionService;
use App\Helpers\AlgerianProvinces;

class Commande extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'specialist',
        'statue',
        'budget',
        'budget_currency',
        'address',
        'media',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'budget' => 'decimal:2',
        'media' => 'array',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['budget_sar', 'currency_symbol', 'province_name'];
    
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'budget_currency' => 'DZD',
    ];
    
    /**
     * الحصول على الميزانية مُحولة إلى الريال السعودي
     *
     * @return float
     */
    public function getBudgetSarAttribute()
    {
        $conversionService = new CurrencyConversionService();
        return $conversionService->convertDZDtoSAR($this->budget);
    }
    
    /**
     * الحصول على رمز العملة للميزانية
     *
     * @return string
     */
    public function getCurrencySymbolAttribute()
    {
        $conversionService = new CurrencyConversionService();
        return $conversionService->getCurrencySymbol($this->budget_currency);
    }

    /**
     * الحصول على اسم الولاية
     *
     * @return string|null
     */
    public function getProvinceNameAttribute()
    {
        return $this->address ? AlgerianProvinces::getProvinceName($this->address) : null;
    }

    /**
     * Get the user that owns the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 