@extends('layouts.app')
@section('title', 'Irrigation Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Driftline designs and installs residential, commercial, drip, and smart irrigation systems with pressure-balanced zones.</p>
        <div class="grid-3">
            @foreach([
                ['residential-sprinklers','Residential Sprinklers','https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=1200&q=80'],
                ['drip-xeriscape','Drip & Xeriscape','https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=1200&q=80'],
                ['commercial-irrigation','Commercial Campuses','https://images.unsplash.com/photo-1464226184884-fa280b87c309?auto=format&fit=crop&w=1200&q=80'],
                ['smart-controllers','Smart Controllers','https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1200&q=80'],
                ['leak-repair','Leak Detection & Repair','https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1200&q=80'],
                ['seasonal-service','Seasonal Start-Up','https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
