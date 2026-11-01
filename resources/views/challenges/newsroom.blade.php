@extends('layouts.app')
@section('title', 'Newsroom challenge')
@section('content')
<x-challenge number="03" project="NEWSROOM" title="Put tomorrow’s stories in focus."
    scenario="Editors need a short list of articles due tomorrow so they can check in with the right journalists."
    task="Add a date condition to the Eloquent query so it returns only assignments with tomorrow’s deadline."
    file="app/Http/Controllers/NewsroomController.php" method="index() → $articles query"
    test="Refresh after editing. With fresh seed data, only the robotics and greener-campus stories should remain. Check every deadline against tomorrow’s date."
    learned="An Eloquent query selects just the records your capstone needs. Blade turns those model records into a readable assignment list."
    hint="A date-only comparison ignores the time of day. Laravel provides whereDate() and a today()->addDay() date helper."
    command="NewsroomTest">
    <section class="lab"><div class="lab-header">NEWSROOM / ASSIGNMENT DESK <span class="status pending">Due tomorrow</span></div><div class="lab-body"><h2>The next edition</h2><p>Today: {{ today()->format('M j, Y') }} · Target date: <strong>{{ today()->addDay()->format('M j, Y') }}</strong> (UTC)</p><p><span class="count">{{ $articles->count() }}</span> {{ Str::plural('assignment', $articles->count()) }} returned by your query</p>
        <div class="table-wrap"><table><thead><tr><th>Story / Journalist</th><th>Deadline (UTC)</th></tr></thead><tbody>
            @forelse ($articles as $article)<tr><td><strong>{{ $article->title }}</strong><small>{{ $article->journalist }}</small></td><td>{{ $article->deadline->format('M j, Y') }}<small>{{ $article->deadline->format('H:i') }}</small></td></tr>@empty<tr><td colspan="2">No matching assignments. Refresh the sample dates with php artisan db:seed.</td></tr>@endforelse
        </tbody></table></div><p class="callout">The starter shows all five assignments. The completed query shows two when sample data was seeded today. Re-seed before a new workshop day.</p>
        <a class="button secondary" href="{{ route('newsroom') }}">Refresh assignments ↻</a>
    </div></section>
</x-challenge>
@endsection
