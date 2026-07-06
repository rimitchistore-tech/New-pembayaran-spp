<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spp extends Model
{
    use SoftDeletes;

    protected $table = 'spp';

    protected $fillable = [
        'name',
        'academic_year',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: SPP has many Payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get active SPP
     */
    public static function active()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get total collected for this SPP
     */
    public function totalCollected(): float
    {
        return $this->payments()
            ->where('status', 'paid')
            ->sum('amount') ?? 0;
    }
}
