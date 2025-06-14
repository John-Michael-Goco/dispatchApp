<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Responder;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $query = User::with('userInfo')
            ->where('id', '!=', Auth::id())  // Exclude current user
            ->where(function($q) {
                $q->where('name', '!=', 'admin')  // Exclude default admin
                   ->orWhere('role', '!=', 'admin');  // Include non-admin users
            });

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('role')) {
            $query->where('role', $request->input('role'));
        }

        // Handle sorting
        if ($request->has('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($request->input('sort'), $direction);
        } else {
            $query->latest();
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.users.create', compact('services'));
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
            'role' => 'required|in:user,admin,responder',
            'email' => 'required|email',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'responder_code' => 'required_if:role,responder|string',
            'service_id' => 'required_if:role,responder|exists:services,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Create user info
        UserInfo::create([
            'user_id' => $user->id,
            'email' => $validated['email'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        // If user is a responder, create responder record
        if ($validated['role'] === 'responder') {
            Responder::create([
                'user_id' => $user->id,
                'service_id' => $validated['service_id'],
                'responder_code' => $validated['responder_code'],
                'status' => 'inactive',
                'longitude' => 0, 
                'latitude' => 0,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('userInfo');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load(['userInfo', 'responder']);
        $services = Service::all();
        return view('admin.users.edit', compact('user', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|size:10|regex:/^[0-9]{10}$/|unique:users,phone,' . $user->id,
            'role' => 'required|in:user,admin,responder',
            'email' => 'required|email',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'responder_code' => 'required_if:role,responder|string',
            'service_id' => 'required_if:role,responder|exists:services,id',
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ]);

        // Update user info
        $user->userInfo->update([
            'email' => $validated['email'],
            'address' => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
        ]);

        // Handle responder information
        if ($validated['role'] === 'responder') {
            if ($user->responder) {
                // Update existing responder record
                $user->responder->update([
                    'service_id' => $validated['service_id'],
                    'responder_code' => $validated['responder_code'],
                ]);
            } else {
                // Create new responder record
                Responder::create([
                    'user_id' => $user->id,
                    'service_id' => $validated['service_id'],
                    'responder_code' => $validated['responder_code'],
                    'status' => 'active', // Default status
                    'longitude' => 0,
                    'latitude' => 0,
                ]);
            }
        } else if ($user->responder) {
            // If role is changed from responder to something else, delete the responder record
            $user->responder->delete();
        }

        return redirect()->route('admin.users.edit', compact('user'))
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
