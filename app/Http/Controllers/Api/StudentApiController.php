<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentApiController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request): JsonResponse
    {
        $query = Student::with('class');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->paginate(20);

        return response()->json($students);
    }

    /**
     * Display payment summary for a student
     */
    public function paymentSummary(Student $student): JsonResponse
    {
        return response()->json($student->paymentSummary());
    }
}
