@extends('layouts.app')
@section('title', 'Access denied')
@section('content')
<section class="error-page"><p class="error-code">HTTP 403 / AUTHORIZATION DENIED</p><h1>Access denied.</h1><p>Laravel’s server-side Gate refused access. No confidential investigation notes were loaded.</p><div class="identity-current">Current identity<strong>{{ auth()->user()?->name ?? 'Guest' }}</strong></div><p>Omar and guests should see this page. Sara should be able to view the investigation after you complete the Gate condition.</p><a class="button" href="{{ route('banking') }}">Back to banking →</a></section>
@endsection
