<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of classes
     */
    public function index(): View
    {
        $classes = SchoolClass::withCount('students')->paginate(15);
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create(): View
    {
        return view('classes.create');
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|unique:classes|string|max:50',
            'name' => 'required|string|max:255',
        ]);

        SchoolClass::create($validated);

        return redirect()->route('classes.index')
                        ->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified class
     */
    public function show(SchoolClass $class): View
    {
        $class->load('students');
        $studentsWithDebt = $class->studentsWithDebt()->count();

        return view('classes.show', compact('class', 'studentsWithDebt'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(SchoolClass $class): View
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|unique:classes,code,' . $class->id,
            'name' => 'required|string|max:255',
        ]);

        $class->update($validated);

        return redirect()->route('classes.show', $class)
                        ->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Remove the specified class
     */
    public function destroy(SchoolClass $class): RedirectResponse
    {
        if ($class->students()->count() > 0) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa');
        }

        $class->delete();

        return redirect()->route('classes.index')
                        ->with('success', 'Kelas berhasil dihapus');
    }
}
