@extends('layouts.app')
@section('title', 'Bus challenge')
@section('content')
<x-challenge number="02" project="BUS MANAGEMENT" title="Give the shuttle a valid location."
    scenario="A campus bus driver submits coordinates. Impossible locations should never reach the database."
    task="Complete latitude and longitude validation: required, numeric, and within -90…90 and -180…180 respectively."
    file="app/Http/Controllers/BusController.php" method="update() → validation rules"
    test="Save 29.3117 / 47.4818, then try latitude 91 or longitude -181. Invalid input should show errors and leave the last location unchanged."
    learned="Laravel validates input before Eloquent saves it. This is the input-processing part of live tracking; a larger capstone could add GPS updates and maps."
    hint="Laravel has a between validation rule. Combine it with required and numeric so the bounds are treated as numbers."
    command="BusTest">
    <section class="lab"><div class="lab-header">CAMPUS / DRIVER CONSOLE <span class="status">Location update</span></div><div class="lab-body"><h2>{{ $bus->name }}</h2><p>Driver: {{ $bus->driver }} · Submit a location manually.</p>
        <form action="{{ route('bus.update') }}" method="post" novalidate>@csrf
            <div class="fields"><div class="field"><label for="latitude">Latitude</label><input id="latitude" name="latitude" type="text" inputmode="decimal" value="{{ old('latitude', $bus->latitude) }}" aria-describedby="latitude-help"><span id="latitude-help" class="small">Between -90 and 90</span>@error('latitude')<span class="inline-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="longitude">Longitude</label><input id="longitude" name="longitude" type="text" inputmode="decimal" value="{{ old('longitude', $bus->longitude) }}" aria-describedby="longitude-help"><span id="longitude-help" class="small">Between -180 and 180</span>@error('longitude')<span class="inline-error">{{ $message }}</span>@enderror</div></div>
            <button class="button" type="submit">Save location →</button>
        </form>
        <div class="result"><h3>LATEST SAVED LOCATION</h3><dl><div><dt>Latitude</dt><dd>{{ $bus->latitude }}</dd></div><div><dt>Longitude</dt><dd>{{ $bus->longitude }}</dd></div></dl><p>Last saved: {{ $bus->updated_at->format('M j, Y · H:i:s') }} UTC</p></div>
        <p class="callout">Try 91 / 47.4818 to test rejection. Browser validation is disabled here so you can observe Laravel’s own validation.</p>
    </div></section>
</x-challenge>
@endsection
