<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SppController extends Controller
{
    /**
     * Display a listing of SPP
     */
    public function index(): View
    {
        $sppList = Spp::paginate(15);
        return view('spp.index', compact('sppList'));
    }

    /**
     * Show the form for creating new SPP
     */
    public function create(): View
    {
        return view('spp.create');
    }

    /**
     * Store a newly created SPP
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0.01',
            'is_active' => 'boolean',
        ]);

        // Deactivate other SPP if this one is active
        if ($request->boolean('is_active')) {
            Spp::where('is_active', true)->update(['is_active' => false]);
        }

        Spp::create($validated);

        return redirect()->route('spp.index')
                        ->with('success', 'SPP berhasil ditambahkan');
    }

    /**
     * Display the specified SPP
     */
    public function show(Spp $spp): View
    {
        $spp->load('payments.student');
        $totalCollected = $spp->totalCollected();
        $totalPayments = $spp->payments()->count();
        $paidPayments = $spp->payments()->where('status', 'paid')->count();

        return view('spp.show', compact('spp', 'totalCollected', 'totalPayments', 'paidPayments'));
    }

    /**
     * Show the form for editing the specified SPP
     */
    public function edit(Spp $spp): View
    {
        return view('spp.edit', compact('spp'));
    }

    /**
     * Update the specified SPP
     */
    public function update(Request $request, Spp $spp): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0.01',
            'is_active' => 'boolean',
        ]);

        // Deactivate other SPP if this one is active
        if ($request->boolean('is_active') && !$spp->is_active) {
            Spp::where('is_active', true)->update(['is_active' => false]);
        }

        $spp->update($validated);

        return redirect()->route('spp.show', $spp)
                        ->with('success', 'SPP berhasil diperbarui');
    }

    /**
     * Remove the specified SPP
     */
    public function destroy(Spp $spp): RedirectResponse
    {
        if ($spp->payments()->count() > 0) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus SPP yang memiliki pembayaran');
        }

        $spp->delete();

        return redirect()->route('spp.index')
                        ->with('success', 'SPP berhasil dihapus');
    }
}
