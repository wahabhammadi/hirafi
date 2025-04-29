<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Craftsman extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'phone_secondary',
        'avatar',
        'api_id',
        'craft',
        'num_projects',
        'num_clients',
        'reviews',
        'rating',
    ];

    /**
     * Get the user that owns the craftsman.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the offers for the craftsman.
     */
    public function offers()
    {
        return $this->hasMany(Offre::class);
    }
} 