<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PersonalInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('id', Auth::id())->with('userInfo')->first();
        return view('admin.personal-info.index', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        // Ensure the user can only update their own information
        if ($user->id != $id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:user_info,email,' . $user->userInfo->id,
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);


        $user->userInfo->update($validated);


        return redirect()
            ->route('admin.personal-info.index')
            ->with('success', 'Personal information updated successfully.');
    }
}
