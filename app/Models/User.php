<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Helpers\AlgerianProvinces;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url', 'full_name', 'province_name'
    ];

    /**
     * Get the craftsman associated with the user.
     */
    public function craftsman()
    {
        return $this->hasOne(Craftsman::class);
    }

    /**
     * Check if the user is a craftsman.
     */
    public function isCraftsman()
    {
        return $this->role === 'craftsman';
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->surname ? "{$this->name} {$this->surname}" : $this->name;
    }

    /**
     * Get the user's avatar URL.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png');
    }

    /**
     * Get the projects owned by the user.
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * Get the portfolio works of the user.
     */
    public function works()
    {
        return $this->hasMany(Work::class);
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
}
