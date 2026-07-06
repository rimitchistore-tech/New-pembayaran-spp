<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'reference_number',
        'amount',
        'status',
        'description',
        'proof_file',
        'payment_date',
        'verified_by',
        'verified_date',
        'rejection_reason',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'verified_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function verifications()
    {
        return $this->hasMany(PaymentVerification::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Methods
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isVerified()
    {
        return $this->status === 'verified';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Generate reference number
     */
    public static function generateReferenceNumber()
    {
        $date = now()->format('Ym');
        $count = static::whereYear('created_at', now()->year)
                       ->whereMonth('created_at', now()->month)
                       ->count() + 1;
        return 'INV-' . $date . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
