<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    public function index()
    {
        return view('challenges.ai-advisor', ['recommendations' => Recommendation::latest('id')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['interest' => ['required', 'in:programming,biology,design']]);
        $major = match ($validated['interest']) {
            // TODO CHALLENGE 5: Map the three interests to majors (three lines).
            // TODO: Add the programming match arm here.
            // TODO: Add the biology match arm here.
            // TODO: Add the design match arm here.
            default => 'Explore your options',
        };
        Recommendation::create(['interest' => $validated['interest'], 'major' => $major]);

        return redirect()->route('ai-advisor')->with('message', 'Recommendation saved. Check that the suggested major matches your interest.');
    }
}
