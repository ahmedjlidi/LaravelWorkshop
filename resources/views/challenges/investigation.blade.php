@extends('layouts.app')
@section('title', 'Access granted')
@section('content')
<a class="back" href="{{ route('banking') }}">← Back to banking</a>
<p class="eyebrow">BANKING / SERVER AUTHORIZATION RESULT</p><h1 class="challenge-title">Access granted.</h1>
<div class="notice" role="status">HTTP 200 · Laravel authorized {{ auth()->user()->name }}.</div>
<section class="lab"><div class="lab-header">CONFIDENTIAL INVESTIGATION <span class="status">Authorized</span></div><div class="lab-body"><h2>Compliance review</h2><p>{{ $investigation->summary }}</p></div></section>
<p class="callout">Now switch to Omar and try the same URL. Both outcomes are needed to verify this challenge.</p>
@endsection
