<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        return view('challenges.bus', ['bus' => Bus::firstOrFail()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // TODO CHALLENGE 2: Require numeric coordinates within Earth's bounds (two lines).
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);
        Bus::firstOrFail()->update($validated);

        return redirect()->route('bus')->with('message', 'Location saved. Compare the latest coordinates below.');
    }
}
