<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     */
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_code' => 'required|unique:units|string|max:255',
            'unit_type' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        Unit::create($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'unit_code' => 'required|string|max:255|unique:units,unit_code,' . $unit->id,
            'unit_type' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        $unit->update($validated);

        return redirect()->route('admin.units.edit', $unit)
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
