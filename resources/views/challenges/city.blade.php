@extends('layouts.app')
@section('title', 'City challenge')
@section('content')
<x-challenge number="04" project="CITY REPORTING" title="Make every report actionable."
    scenario="A resident reports a broken streetlight. The maintenance team needs a useful description and a location."
    task="Require a string description of at least 10 characters and a nonempty string location before creating a report."
    file="app/Http/Controllers/CityController.php" method="store() → validation rules"
    test="Try an empty form, a short description, and a missing location. Then submit a complete report: it should appear below with status Pending."
    learned="Validation protects data quality, Eloquent creates the record, and the controller assigns Pending on the server. This is a small but complete capstone CRUD workflow."
    hint="Use required and string for both fields. Add a minimum length to the description; keep status out of user-controlled input."
    command="CityTest">
    <section class="lab"><div class="lab-header">CITY / RESIDENT PORTAL <span class="status">New issue</span></div><div class="lab-body"><h2>Something needs attention?</h2><p>Tell the community team what happened and where to find it.</p>
        <form action="{{ route('city.store') }}" method="post" novalidate>@csrf
            <div class="field"><label for="description">What is the issue?</label><textarea id="description" name="description" placeholder="The streetlight outside the library is broken…">{{ old('description') }}</textarea><span class="small">At least 10 characters</span>@error('description')<span class="inline-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="location">Where is it?</label><input id="location" name="location" value="{{ old('location') }}" placeholder="Library entrance, Block B">@error('location')<span class="inline-error">{{ $message }}</span>@enderror</div>
            <button class="button" type="submit">Submit community report →</button>
        </form>
        <div class="divider"></div><h2>Community reports <span class="small">({{ $reports->count() }})</span></h2>
        @forelse ($reports as $report)<article class="report"><div class="report-top"><p>{{ $report->description }}</p><span class="status pending">{{ $report->status }}</span></div><small>{{ $report->location ?: 'Location missing — review your validation' }} · Report #{{ $report->id }}</small></article>@empty<p>No reports yet. Submit the first one above.</p>@endforelse
    </div></section>
</x-challenge>
@endsection
