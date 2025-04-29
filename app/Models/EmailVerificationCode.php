<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'code',
        'is_used',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the verification code.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * التحقق من صلاحية الرمز
     */
    public function isValid()
    {
        return !$this->is_used && now()->lt($this->expires_at);
    }
}
