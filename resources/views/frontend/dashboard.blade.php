@extends('frontend.layout')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-grid">

    <div class="stat-card">
        <div class="stat-title">Total Loker</div>
        <div class="stat-value">32</div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Terisi</div>
        <div class="stat-value">8</div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Menunggu Ambil</div>
        <div class="stat-value">5</div>
    </div>

    <div class="stat-card">
        <div class="stat-title">Maintenance</div>
        <div class="stat-value">1</div>
    </div>

</div>

<div style="margin-top:20px;">
    <div class="card">
        <h3>Ringkasan Terbaru</h3>
        <p>Daftar penitipan terbaru akan muncul di sini. Integrasikan backend untuk menampilkan data nyata.</p>
    </div>
</div>
@endsection