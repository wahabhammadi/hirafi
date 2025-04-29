<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completion_date',
        'media',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completion_date' => 'date',
        'media' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the user that owns the work.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
