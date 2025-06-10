<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     */
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return response()->json($units);
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_code' => 'required|unique:units|string|max:255',
            'unit_type' => 'required|string|max:255',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $unit = Unit::create($request->all());
        return response()->json($unit, 201);
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        return response()->json($unit);
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validator = Validator::make($request->all(), [
            'unit_code' => 'nullable|string|max:255|unique:units,unit_code,' . $unit->id,
            'unit_type' => 'nullable|string|max:255',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'status' => 'nullable|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $unit->update($request->all());
        return response()->json($unit);
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return response()->json(['message' => 'Unit deleted successfully']);
    }
} 