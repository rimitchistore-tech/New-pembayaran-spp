<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nisn',
        'name',
        'class_id',
        'user_id',
        'gender',
        'address',
        'phone',
        'birth_date',
        'nis',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Relationship: Student belongs to Class
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relationship: Student has many Payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relationship: Student has User (optional)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total amount that needs to be paid (pending + overdue)
     */
    public function totalDue(): float
    {
        return $this->payments()
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('amount') ?? 0;
    }

    /**
     * Get total amount already paid
     */
    public function totalPaid(): float
    {
        return $this->payments()
            ->where('status', 'paid')
            ->sum('amount') ?? 0;
    }

    /**
     * Get pending payments count
     */
    public function pendingPaymentsCount(): int
    {
        return $this->payments()
            ->whereIn('status', ['pending', 'overdue'])
            ->count();
    }

    /**
     * Get payment status summary
     */
    public function paymentSummary(): array
    {
        return [
            'total_due' => $this->totalDue(),
            'total_paid' => $this->totalPaid(),
            'pending_count' => $this->pendingPaymentsCount(),
            'status' => $this->totalDue() == 0 ? 'lunas' : 'belum_lunas',
        ];
    }
}
