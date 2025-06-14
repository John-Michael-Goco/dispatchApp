<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResponderController extends Controller
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
        $query = Responder::with(['user', 'service']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->input('service_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $responders = $query->latest()->paginate(10);
        $services = Service::orderBy('name')->get();

        return view('admin.responders.index', compact('responders', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::orderBy('name')->get();
        $users = User::where('role', 'responder')
            ->whereDoesntHave('responder')
            ->get();
        return view('admin.responders.create', compact('services', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance,busy',
        ]);

        // Generate unique responder code
        $validated['responder_code'] = 'RES-' . strtoupper(Str::random(8));

        $responder = Responder::create($validated);

        return redirect()->route('admin.responders.index')
            ->with('success', 'Responder created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Responder $responder)
    {
        $responder->load(['user', 'service']);
        return view('admin.responders.show', compact('responder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Responder $responder)
    {
        $services = Service::orderBy('name')->get();
        return view('admin.responders.edit', compact('responder', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Responder $responder)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'status' => 'required|in:active,inactive,maintenance,busy',
        ]);

        $responder->update($validated);

        return redirect()->route('admin.responders.index')
            ->with('success', 'Responder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Responder $responder)
    {
        $responder->delete();
        return redirect()->route('admin.responders.index')
            ->with('success', 'Responder deleted successfully.');
    }
}
