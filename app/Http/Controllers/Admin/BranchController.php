<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Branch::with('service');

        // Apply service filter if selected
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $branches = $query->latest()->paginate(10)->withQueryString();
        
        // Get all services for the filter dropdown
        $services = Service::orderBy('name')->get();

        return view('admin.branches.index', compact('branches', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.branches.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        Branch::create($validator->validated());

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        // Load the service relationship
        $branch->load('service');
        
        return view('admin.branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $services = Service::orderBy('name')->get();
        return view('admin.branches.edit', compact('branch', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branch->update($validator->validated());

        return redirect()
            ->route('admin.branches.edit', $branch)
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        try {
            $branch->delete();
            return redirect()
                ->route('admin.branches.index')
                ->with('success', 'Branch deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.branches.index')
                ->with('error', 'Unable to delete branch. It may be associated with other records.');
        }
    }
}
