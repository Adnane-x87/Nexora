@extends('layouts.app')

@section('title', 'About Us — NEXORA')

@push('styles')
<style>
    .about-hero {
        padding: 120px 48px 60px;
        text-align: center;
        background: linear-gradient(180deg, var(--bg) 0%, var(--bg2) 100%);
    }
    .about-title {
        font-family: var(--font-display);
        font-size: clamp(40px, 5vw, 72px);
        font-weight: 800;
        margin-bottom: 24px;
        letter-spacing: -2px;
    }
    .about-subtitle {
        color: var(--white-dim);
        font-size: 18px;
        max-width: 600px;
        margin: 0 auto 40px;
    }
    .about-content {
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 48px;
    }
    .about-section {
        margin-bottom: 80px;
    }
    .about-section h2 {
        font-family: var(--font-display);
        font-size: 32px;
        margin-bottom: 20px;
        color: var(--accent);
    }
    .about-section p {
        font-size: 16px;
        color: var(--white-dim);
        line-height: 1.8;
        margin-bottom: 20px;
    }
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }
    .value-card {
        background: var(--bg2);
        padding: 30px;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        transition: transform 0.3s ease;
    }
    .value-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent);
    }
    .value-icon {
        font-size: 32px;
        margin-bottom: 20px;
    }
    .value-title {
        font-family: var(--font-display);
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .value-desc {
        font-size: 14px;
        color: var(--white-dim);
    }
</style>
@endpush

@section('content')
<section class="about-hero">
    <h1 class="about-title">WE ARE <span class="accent-word">NEXORA.</span></h1>
    <p class="about-subtitle">Defining the next generation of electronics with precision engineering and futuristic design.</p>
</section>

<div class="about-content">
    <section class="about-section">
        <h2>Our Mission</h2>
        <p>At NEXORA, we believe that technology should be an extension of human potential. Our mission is to provide cutting-edge devices that empower creators, thinkers, and explorers to push the boundaries of what's possible.</p>
        <p>Founded in 2024, we've quickly become a destination for those who refuse to settle for the ordinary. We curate and engineer products that blend high performance with an aesthetic that looks forward, not back.</p>
    </section>

    <section class="about-section">
        <h2>Our Values</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon"><i data-lucide="rocket"></i></div>
                <div class="value-title">Innovation</div>
                <div class="value-desc">We constantly seek out the latest breakthroughs to bring you the future today.</div>
            </div>
            <div class="value-card">
                <div class="value-icon"><i data-lucide="gem"></i></div>
                <div class="value-title">Quality</div>
                <div class="value-desc">Every product in our catalog undergoes rigorous testing for durability and performance.</div>
            </div>
            <div class="value-card">
                <div class="value-icon"><i data-lucide="globe"></i></div>
                <div class="value-title">Sustainability</div>
                <div class="value-desc">Committed to reducing our footprint through efficient logistics and eco-conscious packaging.</div>
            </div>
        </div>
    </section>
</div>
@endsection
