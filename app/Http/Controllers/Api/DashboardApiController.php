<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Models\Payment;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_students' => Student::count(),
            'total_classes' => SchoolClass::count(),
            'total_payments' => Payment::count(),
            'total_collected' => Payment::where('status', 'paid')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'overdue_payments' => Payment::where('status', 'overdue')->count(),
        ]);
    }

    /**
     * Get monthly report
     */
    public function monthlyReport(): JsonResponse
    {
        $report = Payment::selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->groupByRaw('YEAR(payment_date), MONTH(payment_date)')
            ->orderByRaw('YEAR(payment_date) DESC, MONTH(payment_date) DESC')
            ->get();

        return response()->json($report);
    }

    /**
     * Get report grouped by class
     */
    public function reportByClass(): JsonResponse
    {
        $report = SchoolClass::withCount('students')
            ->with(['students' => function ($query) {
                $query->select('id', 'class_id');
            }])
            ->get()
            ->map(function ($class) {
                $totalDue = $class->students->sum(fn ($student) => $student->totalDue());
                $totalPaid = $class->students->sum(fn ($student) => $student->totalPaid());

                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'code' => $class->code,
                    'students_count' => $class->students_count,
                    'total_due' => $totalDue,
                    'total_paid' => $totalPaid,
                ];
            });

        return response()->json($report);
    }
}
