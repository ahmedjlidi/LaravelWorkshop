<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Five small Laravel challenges inspired by university capstone projects.">
    <title>@yield('title', 'Capstone Lab') · Laravel Workshop</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('workshop.css') }}">
</head>
<body>
<div class="shell">
    <header class="topbar">
        <a class="brand" href="{{ route('home') }}"><span class="brandmark" aria-hidden="true">⌘</span> capstone<span><em>/</em>lab</span></a>
        <nav class="navlinks" aria-label="Main navigation">
            <a href="{{ route('home') }}#challenges">The challenges</a>
            <span class="pill"><span class="dot"></span> LARAVEL WORKSHOP</span>
        </nav>
    </header>
    <main>@yield('content')</main>
    <footer><span>Built with Laravel. Made for your next big idea.</span><span>PHP + Blade + SQLite <span aria-hidden="true">↗</span> &nbsp; No external services.</span></footer>
</div>
</body>
</html>
