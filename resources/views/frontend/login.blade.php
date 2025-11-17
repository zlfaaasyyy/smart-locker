@extends('frontend.layout')

@section('title', 'Login')

@section('styles')
<!-- Jika ingin style tambahan khusus halaman -->
@endsection

@section('content')
<div class="center-pane">
    <div class="auth-card">
        <h2>Masuk ke Smart Locker</h2>

        <form action="{{ url('/login') }}" method="POST" class="form-stack">
            @csrf
            <label>Email</label>
            <input type="email" name="email" placeholder="email@contoh.com" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>

            <button class="btn btn-primary" type="submit">Login</button>

            <div class="muted" style="text-align:center; margin-top:12px;">
                Belum punya akun? <a href="#">Daftar</a>
            </div>
        </form>
    </div>
</div>
@endsection