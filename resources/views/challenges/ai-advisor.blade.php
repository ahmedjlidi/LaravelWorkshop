@extends('layouts.app')
@section('title', 'AI Advisor challenge')
@section('content')
<x-challenge number="05" project="AI ADVISOR" title="Turn an interest into a direction."
    scenario="A graduate shares an interest and receives a possible university major, saved as a recommendation report."
    task="Map programming to Computer Science, biology to Medicine, and design to Architecture in the match expression."
    file="app/Http/Controllers/AdvisorController.php" method="store() → $major match expression"
    test="Submit each of the three interests. The newest report should show the matching major, and remain visible after refreshing the page."
    learned="A controller combines PHP logic with Eloquent persistence. A real capstone could replace this rule-based component with an AI service while Laravel continues managing the application."
    hint="Each match arm has an input value, =&gt;, an output value, and a comma. The select sends lowercase interest values."
    command="AdvisorTest">
    <section class="lab"><div class="lab-header">GUIDANCE / INTEREST QUESTIONNAIRE <span class="status pending">Rule-based demo</span></div><div class="lab-body"><h2>What sparks your curiosity?</h2><p>This is a <strong>rule-based educational placeholder</strong>, not an actual AI-powered career advisor. It demonstrates code flow; it is not personal career advice.</p>
        <form action="{{ route('ai-advisor.store') }}" method="post">@csrf
            <div class="field"><label for="interest">Choose an interest</label><select id="interest" name="interest"><option value="programming" @selected(old('interest') === 'programming')>Programming</option><option value="biology" @selected(old('interest') === 'biology')>Biology</option><option value="design" @selected(old('interest') === 'design')>Design</option></select></div>
            <button class="button" type="submit">Generate recommendation →</button>
        </form>
        <div class="divider"></div><h2>Saved recommendation reports</h2><p>The newest report appears first. Check the mapping, not just the success message.</p>
        @forelse ($recommendations as $recommendation)<article class="report"><div class="report-top"><span class="small">{{ ucfirst($recommendation->interest) }}</span><span class="small">REPORT #{{ $recommendation->id }}</span></div><h3>{{ $recommendation->major }}</h3><small>Rule-based suggestion · {{ $recommendation->created_at->format('M j, Y · H:i') }} UTC</small></article>@empty<p>No recommendations yet. Choose an interest to create one.</p>@endforelse
    </div></section>
</x-challenge>
@endsection
