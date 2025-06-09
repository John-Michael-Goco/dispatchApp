<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewEmergencyCreated;

class EmergencyController extends Controller
{
    public function index()
    {
        $emergencies = Emergency::with('user')->latest()->paginate(10);
        return response()->json($emergencies);
    }

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

        return response()->json($emergency, 201);
    }

    public function show(Emergency $emergency)
    {
        return response()->json($emergency->load('user'));
    }

    public function update(Request $request, Emergency $emergency)
    {
        $validated = $request->validate([
            'incident' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validated['user_id'] = Auth::id();

        $emergency->update($validated);

        return response()->json($emergency);
    }

    public function destroy(Emergency $emergency)
    {
        $emergency->delete();

        return response()->json(['message' => 'Emergency deleted successfully']);
    }
} 
?>