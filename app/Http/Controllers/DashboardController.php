<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\SchoolClass;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index(): View
    {
        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();
        $totalPayments = Payment::count();
        $totalCollected = Payment::where('status', 'paid')->sum('amount');
        
        $pendingPayments = Payment::where('status', 'pending')->count();
        $overduePayments = Payment::where('status', 'overdue')->count();
        
        // Students with debt
        $studentsWithDebt = Student::whereHas('payments', function ($query) {
            $query->whereIn('status', ['pending', 'overdue']);
        })->count();

        // Recent payments
        $recentPayments = Payment::with('student', 'spp')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Class statistics
        $classStats = SchoolClass::withCount('students')
            ->get()
            ->map(function ($class) {
                $class->students_with_debt = $class->studentsWithDebt()->count();
                return $class;
            });

        return view('dashboard', compact(
            'totalStudents',
            'totalClasses',
            'totalPayments',
            'totalCollected',
            'pendingPayments',
            'overduePayments',
            'studentsWithDebt',
            'recentPayments',
            'classStats'
        ));
    }
}
