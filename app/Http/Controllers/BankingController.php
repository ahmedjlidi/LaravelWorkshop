<?php

namespace App\Http\Controllers;

use App\Models\{Investigation, Transaction, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Gate};

class BankingController extends Controller
{
    public function index()
    {
        return view('challenges.banking', ['transaction' => Transaction::firstOrFail()]);
    }

    public function identity(Request $request)
    {
        // Deliberately local demo identities; no login implementation is required.
        $data = $request->validate(['identity' => ['required', 'in:employee,compliance_officer']]);
        Auth::login(User::where('role', $data['identity'])->firstOrFail());
        $request->session()->regenerate();

        return redirect()->route('banking')->with('message', 'Demo identity changed. Now open the investigation.');
    }

    public function investigation()
    {
        // Authorization happens BEFORE confidential data is retrieved.
        Gate::authorize('view-investigation');

        return view('challenges.investigation', ['investigation' => Investigation::firstOrFail()]);
    }
}
