<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responder;
use App\Models\Service;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        return view('admin.responders.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^[0-9]{10}$/|unique:users',
            'password' => 'required|string|min:8',
            'email' => 'required|email|unique:user_info',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'service_id' => 'required|exists:services,id',
            'responder_code' => 'required|string|max:255|unique:responders',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'responder',
            ]);

            // Create user info
            UserInfo::create([
                'user_id' => $user->id,
                'email' => $validated['email'],
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
            ]);

            // Create responder
            Responder::create([
                'user_id' => $user->id,
                'service_id' => $validated['service_id'],
                'responder_code' => $validated['responder_code'],
                'status' => 'inactive',
                'longitude' => 0,
                'latitude' => 0,
            ]);

            DB::commit();
            return redirect()->route('admin.responders.index')
                ->with('success', 'Responder created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create responder. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Responder $responder)
    {
        $responder->load(['user.userInfo', 'service']);
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
            'responder_code' => 'required|string|max:255|unique:responders,responder_code,' . $responder->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^[0-9]{10}$/|unique:users,phone,' . $responder->user->id,
            'email' => 'required|email|max:255|unique:user_info,email,' . $responder->user->userInfo->id,
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Update responder
            $responder->update([
                'service_id' => $validated['service_id'],
                'responder_code' => $validated['responder_code'],
            ]);

            // Update user
            $responder->user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);

            // Update user info
            $responder->user->userInfo->update([
                'email' => $validated['email'],
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
            ]);

            DB::commit();
            return redirect()->route('admin.responders.index')
                ->with('success', 'Responder updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update responder. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Responder $responder)
    {
        DB::beginTransaction();
        try {
            // Delete the responder record
            $responder->delete();
            
            // Delete the associated user and user info (cascade delete)
            $responder->user->delete();

            DB::commit();
            return redirect()->route('admin.responders.index')
                ->with('success', 'Responder deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete responder. Please try again.');
        }
    }
}
