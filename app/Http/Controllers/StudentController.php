<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request): View
    {
        $query = Student::with('class');

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Search by name or NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(15);
        $classes = SchoolClass::all();

        return view('students.index', compact('students', 'classes'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create(): View
    {
        $classes = SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created student in database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:students|string|max:20',
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'nis' => 'nullable|string|max:20',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
                        ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student): View
    {
        $student->load(['class', 'payments.spp']);
        $paymentSummary = $student->paymentSummary();

        return view('students.show', compact('student', 'paymentSummary'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student): View
    {
        $classes = SchoolClass::all();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified student in database
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'nis' => 'nullable|string|max:20',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
                        ->with('success', 'Siswa berhasil diperbarui');
    }

    /**
     * Remove the specified student from database
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'Siswa berhasil dihapus');
    }
}
