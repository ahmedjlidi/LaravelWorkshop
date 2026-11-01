@props(['number', 'project', 'title', 'scenario', 'task', 'file', 'method', 'test', 'learned', 'hint', 'command'])
<a class="back" href="{{ route('home') }}#challenges">← All challenges</a>
<p class="eyebrow">CHALLENGE {{ $number }} / {{ $project }} / 5–8 MIN</p>
<h1 class="challenge-title">{{ $title }}</h1>
<p class="subtitle">A few lines in your editor. A visible difference in your browser.</p>
<div class="challenge-grid">
    <aside class="instructions" aria-label="Challenge instructions">
        <section class="instruction"><h2>01 / Scenario</h2><p>{{ $scenario }}</p></section>
        <section class="instruction"><h2>02 / Your task</h2><p>{{ $task }}</p><code class="file">{{ $file }}<br><strong>{{ $method }}</strong></code><span class="small">Path relative to laravel-capstone-workshop/</span></section>
        <section class="instruction"><h2>03 / Test the result</h2><p>{{ $test }}</p><code class="file">php artisan test --filter={{ $command }}</code><p class="small">Run in your terminal. Passing backend tests verify the behavior.</p></section>
        <section class="instruction"><details><summary>A small hint</summary><p>{{ $hint }}</p></details></section>
        <section class="instruction"><h2>What you learned</h2><p>{{ $learned }}</p></section>
    </aside>
    <div>
        @if (session('message'))<div class="notice" role="status">{{ session('message') }}</div>@endif
        @if ($errors->any())<div class="notice errors" role="alert"><strong>Let’s check that input.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        {{ $slot }}
    </div>
</div>
