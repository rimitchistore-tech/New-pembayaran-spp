<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'spp_id',
        'amount',
        'status',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Relationship: Payment belongs to Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: Payment belongs to SPP
     */
    public function spp(): BelongsTo
    {
        return $this->belongsTo(Spp::class);
    }

    /**
     * Relationship: Payment processed by User (Officer)
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Relationship: Payment has many History records
     */
    public function history(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    /**
     * Check if payment is overdue (pending more than 30 days)
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        return $this->created_at->addDays(30)->isPast();
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(string $method = 'cash', string $referenceNumber = null, int $userId = null): void
    {
        $this->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $method,
            'reference_number' => $referenceNumber,
            'processed_by' => $userId,
        ]);

        // Log history
        $this->history()->create([
            'action' => 'confirmed',
            'user_id' => $userId,
            'old_status' => 'pending',
            'new_status' => 'paid',
            'description' => "Pembayaran dikonfirmasi via $method",
        ]);
    }
}
