<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'code',
        'name',
        'total_students',
    ];

    /**
     * Relationship: Class has many Students
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Get total students in class
     */
    public function getTotalStudents(): int
    {
        return $this->students()->count();
    }

    /**
     * Get students who haven't paid all their fees
     */
    public function studentsWithDebt(): HasMany
    {
        return $this->students()->whereHas('payments', function ($query) {
            $query->whereIn('status', ['pending', 'overdue']);
        });
    }
}
