@extends('layouts.app')
@section('title', 'Banking challenge')
@section('content')
<x-challenge number="01" project="BANKING" title="Keep the investigation confidential."
    scenario="A bank employee can see a transaction, but confidential investigation notes belong to compliance officers."
    task="Complete the Gate condition so only the compliance_officer role can open the investigation."
    file="app/Providers/AppServiceProvider.php" method="boot() → view-investigation Gate"
    test="Choose each identity below, apply it, and open the investigation. Sara should receive access; Omar should receive HTTP 403."
    learned="Authentication tells Laravel who you are. A Gate checks what you may do, protecting the capstone’s data on the server."
    hint="The Gate receives an authenticated User. Compare its role with the required role using strict equality. A guest is denied automatically."
    command="BankingTest">
    <section class="lab"><div class="lab-header">BANK / INTERNAL WORKSPACE <span class="status pending">Flagged for review</span></div><div class="lab-body">
        <p class="eyebrow">TRANSACTION {{ $transaction->reference }}</p><h2>{{ $transaction->customer }}</h2><p>Amount: KWD {{ number_format($transaction->amount, 2) }} · General transaction information</p>
        <div class="divider"></div><h2>Step into a demo identity</h2><p>This local workshop switch signs you in as a seeded user.</p>
        <form action="{{ route('banking.identity') }}" method="post">@csrf
            <div class="identity"><input id="employee" type="radio" name="identity" value="employee" @checked(! auth()->check() || auth()->user()->role === 'employee')><label for="employee">Omar · Bank employee<span>employee — must receive HTTP 403</span></label></div>
            <div class="identity"><input id="compliance" type="radio" name="identity" value="compliance_officer" @checked(auth()->check() && auth()->user()->role === 'compliance_officer')><label for="compliance">Sara · Compliance officer<span>compliance_officer — should receive access</span></label></div>
            <button class="button secondary" type="submit">Use selected identity</button>
        </form>
        <div class="divider"></div><div class="identity-current">Current identity<strong>{{ auth()->user()?->name ?? 'Guest — select an identity above' }}</strong></div>
        <p>The button is visible to everyone. The server makes the access decision.</p><a class="button" href="{{ route('banking.investigation') }}">Open confidential investigation <span aria-hidden="true">→</span></a>
    </div></section>
    <p class="callout">Local demonstration only: the identity switch is intentionally open for testing. A production capstone needs real sign-in and identity management.</p>
</x-challenge>
@endsection
