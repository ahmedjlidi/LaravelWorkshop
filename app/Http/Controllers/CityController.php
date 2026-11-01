<?php

namespace App\Http\Controllers;

use App\Models\IssueReport;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        return view('challenges.city', ['reports' => IssueReport::latest('id')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // TODO CHALLENGE 4: Require a description of at least 10 characters and a location (two lines).
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);
        IssueReport::create([
            'description' => $validated['description'],
            // The fallback keeps the intentionally incomplete starter runnable.
            'location' => $validated['location'] ?? '',
            'status' => 'Pending',
        ]);

        return redirect()->route('city')->with('message', 'Report saved. Find it in the community report list.');
    }
}
