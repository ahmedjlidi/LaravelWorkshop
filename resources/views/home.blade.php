@extends('layouts.app')
@section('title', 'Choose your challenge')
@section('content')
<section class="hero">
    <div>
        <p class="eyebrow">THE CAPSTONE WORKSHOP / HANDS ON WITH LARAVEL</p>
        <h1>Small changes.<br><span>Real impact.</span></h1>
        <p>Your capstone, one feature at a time. Pick a project, write a few lines of PHP, and see Laravel bring your idea to life.</p>
        <div class="hero-actions"><a class="button" href="#challenges">Find your challenge <span aria-hidden="true">↓</span></a><span class="small">One team. One challenge.</span></div>
    </div>
    <div class="code-window" aria-label="Workshop workflow">
        <div class="window-bar"><i></i><i></i><i></i><span>your-next-feature.php</span></div>
        <pre><span class="comment">// A little code goes a long way.</span>
<span class="accent">Route</span>::get(<span class="str">'/your-next-idea'</span>, function () {
    <span class="comment">// 01. Find your team's TODO.</span>
    <span class="comment">// 02. Make a small change.</span>
    <span class="comment">// 03. Test it. See what happens.</span>

    <span class="accent">return</span> view(<span class="str">'something-great'</span>);
});</pre>
        <div class="window-footer">CAPSTONE LAB <span>● Ready to experiment</span></div>
    </div>
</section>
<div class="stats" aria-label="Workshop at a glance">
    <div class="stat"><strong>05</strong><span>real project scenarios</span></div>
    <div class="stat"><strong>5–8</strong><span>minutes per challenge</span></div>
    <div class="stat"><strong>1–5</strong><span>lines to make it work</span></div>
</div>
<section id="challenges">
    <div class="section-head"><div><h2>Where will you make a difference?</h2><p>Five independent challenges. Choose the one that connects to your project.</p></div><span class="pill">BEGINNER FRIENDLY</span></div>
    <div class="grid">
        @foreach ([
            ['banking', '01', '↗', 'Banking', 'Anti-Money-Laundering Detection', 'Keep confidential investigations in the right hands. Decide who gets access.', ['Authentication', 'Authorization']],
            ['bus', '02', '⇄', 'Bus', 'Student Bus Management', 'Help a campus shuttle share its location. Keep impossible coordinates out.', ['Validation', 'Eloquent']],
            ['newsroom', '03', '≡', 'Newsroom', 'Newspaper Organization Management', 'Give editors a clearer view of tomorrow. Find the assignments that matter.', ['Eloquent queries', 'Blade']],
            ['city', '04', '⌖', 'City', 'Community Issue Reporting', 'Turn a resident’s report into action. Make sure every issue has the details it needs.', ['Validation', 'CRUD']],
            ['ai-advisor', '05', '✳', 'AI Advisor', 'University and Career Guidance', 'Connect a student’s interests to a possible major with a simple rule-based recommendation.', ['PHP logic', 'Eloquent']],
        ] as [$route, $number, $icon, $title, $project, $scenario, $tags])
            <article class="card">
                <div class="card-top"><span class="icon" aria-hidden="true">{{ $icon }}</span><span class="num">/ {{ $number }}</span></div>
                <h3>{{ $title }}</h3><p class="project">{{ $project }}</p><p>{{ $scenario }}</p>
                <div class="tags">@foreach ($tags as $tag)<span class="tag">{{ $tag }}</span>@endforeach</div>
                <a class="card-link" href="{{ route($route) }}">Open challenge <span aria-hidden="true">↗</span></a>
            </article>
        @endforeach
        <aside class="card how-card"><p class="eyebrow">THE WORKSHOP LOOP</p><h3>Read. Code.<br>Test. Repeat.</h3><ol><li>Open your team’s challenge.</li><li>Find the marked TODO in your editor.</li><li>Change the code and refresh.</li><li>Run your challenge’s tests.</li></ol><span class="small">No points to collect. Just working code.</span></aside>
    </div>
</section>
<div class="workshop-note"><strong>Your environment is ready.</strong> The routes, models, database, and views are prepared. Focus on your challenge’s small TODO. A successful click alone doesn’t prove the feature works: test both the accepted and rejected cases.</div>
@endsection
