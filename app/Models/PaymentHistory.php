<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    protected $table = 'payment_history';

    protected $fillable = [
        'payment_id',
        'action',
        'user_id',
        'old_status',
        'new_status',
        'description',
    ];

    public $timestamps = true;

    /**
     * Relationship: History belongs to Payment
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Relationship: History recorded by User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
