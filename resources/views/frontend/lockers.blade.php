@extends('frontend.layout')

@section('title', 'Status Loker')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Grid Loker</h3>
        <div>
            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="available">Tersedia</option>
                <option value="occupied">Terpakai</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>

    <div class="lockers-grid" id="lockersGrid">
        @for($i = 1; $i <= 12; $i++)
            @php
                // contoh status dummy
                $mod = $i % 4;
                if($mod == 0) { $status = 'maintenance'; }
                elseif($mod == 1) { $status = 'available'; }
                else { $status = 'occupied'; }
            @endphp

            <div class="locker-card status-{{ $status }}" data-status="{{ $status }}" data-id="{{ $i }}">
                <div class="locker-id">Loker {{ $i }}</div>
                <div class="locker-status">
                    @if($status == 'available')
                        <span class="badge badge-green">Tersedia</span>
                    @elseif($status == 'occupied')
                        <span class="badge badge-red">Terpakai</span>
                    @else
                        <span class="badge badge-gray">Maintenance</span>
                    @endif
                </div>

                <div class="locker-actions">
                    <button class="btn btn-sm btn-outline" onclick="openLockerDetail({{ $i }}, '{{ $status }}')">Detail</button>
                    @if($status == 'available')
                        <button class="btn btn-sm btn-primary" onclick="openDepositModal({{ $i }})">Deposit</button>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>

<!-- Modal templates created by JS -->
@endsection

@section('scripts')
<script>
    // optional inline JS to filter grid quickly (small enhancement)
    document.getElementById('filterStatus')?.addEventListener('change', function(e){
        const v = e.target.value;
        document.querySelectorAll('#lockersGrid .locker-card').forEach(card => {
            if(!v || card.dataset.status === v) card.style.display = '';
            else card.style.display = 'none';
        });
    });
</script>
@endsection