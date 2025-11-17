@extends('frontend.layout')

@section('title', 'Form Penitipan (Deposit)')

@section('content')
<div class="card" style="max-width:800px;">
    <h3>Form Penitipan Barang</h3>

    <form action="{{ url('/deposit') }}" method="POST" class="form-stack">
        @csrf

        <label>Nama</label>
        <input name="name" type="text" placeholder="Nama pemilik barang" required>

        <label>No. WhatsApp</label>
        <input name="phone" type="tel" placeholder="08xxxxxxxxxx" required>

        <label>Deskripsi Barang</label>
        <textarea name="description" rows="3" placeholder="Jenis / warna / catatan"></textarea>

        <label>Pilih Loker</label>
        <select name="locker_id" required>
            <option value="">-- Pilih Loker --</option>
            @for($i=1;$i<=12;$i++)
                <option value="{{ $i }}">Loker {{ $i }}</option>
            @endfor
        </select>

        <div style="display:flex; gap:10px; margin-top:10px;">
            <button class="btn btn-primary" type="submit">Simpan & Kirim WA</button>
            <a class="btn btn-outline" href="{{ url('/lockers') }}">Batal</a>
        </div>
    </form>
</div>
@endsection