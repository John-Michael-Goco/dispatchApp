<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewEmergencyCreated;

class EmergencyController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Emergency::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('incident', 'like', "%{$search}%");
        }

        $emergencies = $query->latest()->paginate(10);

        return view('admin.emergencies.index', compact('emergencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.emergencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validated['user_id'] = Auth::id();

        $emergency = Emergency::create($validated);

        // Load the relationships for broadcasting
        $emergency->load(['user']);

        broadcast(new NewEmergencyCreated($emergency))->toOthers();

        return redirect()->route('admin.emergencies.index')->with('success', 'Emergency created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Emergency $emergency)
    {
        // Mark emergency as read when viewed
        if ($emergency->status === 'unread') {
            $emergency->update(['status' => 'read']);
        }
        
        return view('admin.emergencies.show', compact('emergency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emergency $emergency)
    {
        return view('admin.emergencies.edit', compact('emergency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emergency $emergency)
    {
        $validated = $request->validate([
            'incident' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validated['user_id'] = Auth::id();

        $emergency->update($validated);

        return redirect()->route('admin.emergencies.edit', $emergency)->with('success', 'Emergency updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emergency $emergency)
    {
        $emergency->delete();

        return redirect()->route('admin.emergencies.index')->with('success', 'Emergency deleted successfully.');
    }
}
