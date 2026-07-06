<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'verified_by',
        'action',
        'notes',
        'verification_method',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
