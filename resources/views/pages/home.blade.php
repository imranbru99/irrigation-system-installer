@extends('layouts.app')

@section('title', 'Driftline Irrigation Co.')
@section('meta_description', 'Premium residential, commercial, and smart irrigation systems installed across Austin and Central Texas by Driftline Irrigation Co.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Driftline<br>Irrigation Co.</h1>
        <p>Precision spray zones. Water-wise drip. Smart controllers that keep Central Texas landscapes healthy without wasting a gallon.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Book a water audit</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">View irrigation systems</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Irrigation systems</h2>
        <p class="lede">Purpose-built watering for lawns, beds, commercial campuses, and drought-smart xeriscapes — never a one-size zone map.</p>
        <div class="grid-3">
            @foreach([
                ['residential-sprinklers','Residential Sprinklers','Matched precipitation heads and pressure-balanced zones.','https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=1200&q=80'],
                ['drip-xeriscape','Drip & Xeriscape','Low-flow emitters for beds, trees, and native plantings.','https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=1200&q=80'],
                ['commercial-irrigation','Commercial Campuses','Scalable controllers and zone maps for HOAs and offices.','https://images.unsplash.com/photo-1464226184884-fa280b87c309?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Driftline</h2>
        <p class="lede">Austin crews that treat hydraulics and soil moisture as non-negotiable — because systems fail from the meter out.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Audit-first design</h3>
                <p>Every install starts with pressure tests, soil notes, and a zone map sized to plant needs — not guesswork.</p>
            </div>
            <div class="panel">
                <h3>Water-wise specs</h3>
                <p>Spray for turf, drip for beds, smart Wi‑Fi controllers for schedules that adapt to Austin heat.</p>
            </div>
            <div class="panel">
                <h3>ERP-run jobs</h3>
                <p>Quotes, installs, invoices, and seasonal tune-ups live in one operations stack — fewer dropped balls.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Driftline ERP used to schedule crews, track fittings, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>31</strong> Active jobs</div>
                    <div class="metric"><strong>$142k</strong> Pipeline</div>
                    <div class="metric"><strong>22</strong> Audits booked</div>
                </div>
                <div class="bars">
                    <span style="height:40%"></span><span style="height:65%"></span><span style="height:50%"></span>
                    <span style="height:80%"></span><span style="height:55%"></span><span style="height:90%"></span>
                    <span style="height:70%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & scheduling</li>
                    <li>Valves / heads inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the field</h2>
        <p class="lede">Guides and notes for homeowners and property managers.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
