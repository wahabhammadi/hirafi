<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CurrencyConversionService;

class Offre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'commande_id',
        'craftsman_id',
        'price',
        'price_currency',
        'delivery_date',
        'description',
        'media',
        'status',
        'rating',
        'review',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'delivery_date' => 'date',
        'media' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['price_sar'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'price_currency' => 'DZD',
    ];

    /**
     * الحصول على السعر مُحولاً إلى الريال السعودي
     *
     * @return float
     */
    public function getPriceSarAttribute()
    {
        $conversionService = new CurrencyConversionService();
        return $conversionService->convertDZDtoSAR($this->price);
    }

    /**
     * الحصول على رمز العملة للسعر
     *
     * @return string
     */
    public function getCurrencySymbolAttribute()
    {
        $conversionService = new CurrencyConversionService();
        return $conversionService->getCurrencySymbol($this->price_currency);
    }

    /**
     * Get the user that owns the offer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the commande that the offer is for.
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Get the craftsman that made the offer.
     */
    public function craftsman()
    {
        return $this->belongsTo(Craftsman::class);
    }
}
