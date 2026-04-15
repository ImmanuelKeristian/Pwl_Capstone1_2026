{{-- resources/views/events/guest-index.blade.php --}}
@extends('layouts.master')

@section('title', 'Daftar Event')

@section('web-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><span class="text-muted fw-light">Event /</span> Daftar Event</h4>
        
        <form action="{{ route('events.guest.index') }}" method="GET" class="d-flex">
            <select name="category" class="form-select form-select-sm me-2" onchange="this.form.submit()" aria-label="Filter Kategori Event">
                <option value="">Semua Kategori</option>
                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            @if(request('category'))
            <a href="{{ route('events.guest.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    {{-- AWAL FITUR ANALITIK UNTUK ADMIN/PANITIA/KEUANGAN --}}
    @auth
        @if(auth()->user()->role !== 'member')
        <div class="card bg-transparent shadow-none border-0 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="bx bx-bar-chart-alt-2 me-2"></i>Ringkasan Analitik Event</h5>
                <div>
                    {{-- Di dalam resources/views/events/guest-index.blade.php --}}
                    <a href="{{ route('events.export.excel') }}" class="btn btn-sm btn-success me-1">
                        <i class="bx bx-spreadsheet me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('events.export.pdf') }}" class="btn btn-sm btn-danger">
                        <i class="bx bxs-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>

            <div class="row g-4 mb-4">
                {{-- Total Revenue --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Total Revenue</span>
                                    <div class="d-flex align-items-end mt-2">
                                        <h4 class="mb-0 me-2">Rp 0</h4>
                                    </div>
                                    <small>Pendapatan keseluruhan</small>
                                </div>
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-dollar bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistik Penjualan --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <span>Statistik Penjualan</span>
                                    <div class="d-flex align-items-end mt-2">
                                        <h4 class="mb-0 me-2">0</h4>
                                    </div>
                                    <small>Tiket/Registrasi berhasil</small>
                                </div>
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="bx bx-cart bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Event Performance Analytics --}}
                <div class="col-sm-6 col-xl-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <span>Event Performance Analytics</span>
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="bx bx-trending-up bx-sm"></i>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <h6 class="mb-0">Tingkat Kehadiran</h6>
                                    <span class="text-muted small">0% rata-rata</span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Event Aktif</h6>
                                    <span class="text-muted small">0 event</span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Pertumbuhan</h6>
                                    <span class="text-success small"><i class="bx bx-chevron-up"></i> 0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik Transaksi --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="m-0">Grafik Transaksi Registrasi</h6>
                </div>
                <div class="card-body">
                    {{-- Tempatkan Canvas Chart.js di sini --}}
                    <canvas id="transactionChart" style="height: 250px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
        @endif
    @endauth
    {{-- AKHIR FITUR ANALITIK --}}

    @if(!isset($events) || $events->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            <h4 class="alert-heading">Oops!</h4>
            <p>Saat ini belum ada event yang tersedia @if(request('category')) dalam kategori ini @endif.</p>
            <hr>
            <p class="mb-0">Silakan cek kembali nanti atau pilih kategori lain.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($events as $event)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($event->poster_kegiatan)
                        <img class="card-img-top" 
                             src="{{ asset('storage/' . $event->poster_kegiatan) }}" 
                             alt="Poster {{ $event->title }}" 
                             style="height: 200px; object-fit: cover;">
                    @else
                        <img class="card-img-top" 
                             src="https://placehold.co/600x400/EFEFEF/AAAAAA?text=Event%20Poster" 
                             alt="Poster Default untuk {{ $event->title }}"
                             style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        
                        @if($event->eventCategory)
                        <span class="badge bg-label-primary mb-2">{{ $event->eventCategory->name }}</span>
                        @endif

                        <p class="card-text text-muted small mb-1">
                            <i class="bx bx-calendar bx-xs me-1"></i> 
                            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                            @if($event->end_date && $event->end_date != $event->start_date)
                                - {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y') }}
                            @endif
                        </p>

                        @if($event->lokasi)
                        <p class="card-text text-muted small mb-2">
                            <i class="bx bx-map bx-xs me-1"></i> {{ $event->lokasi }}
                        </p>
                        @endif
                        
                        <p class="card-text mt-0 flex-grow-1">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                
                        
                        <div class="mt-auto pt-2"> 
                            @auth
                                @if(auth()->user()->role == 'member')
                                    @php
                                        $isRegistered = auth()->user()->eventRegistrations()->where('event_id', $event->id)->exists();
                                    @endphp
                                    @if($isRegistered)
                                        <button class="btn btn-sm btn-success w-100" disabled>Sudah Terdaftar</button>
                                    @else
                                        <a href="{{ route('registrations.index', $event->id) }}" class="btn btn-sm btn-primary w-100">Registrasi Event</a>
                                    @endif
                                @else 
                                @endif
                            @else 
                                 <a href="{{ route('register') }}?redirect_to_event={{ $event->id }}" class="btn btn-sm btn-primary w-100">Registrasi untuk Ikut</a>
                            @endauth
                        </div>
                    </div>
                    @if(isset($event->biaya_registrasi))
                    <div class="card-footer text-muted small">
                        Biaya: 
                        @if($event->biaya_registrasi > 0)
                            Rp {{ number_format($event->biaya_registrasi, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
{{-- Script untuk memuat Chart.js (Hanya jika belum dimuat di layout utama) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('transactionChart');
        if (ctx) {
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'], 
                    datasets: [{
                        label: 'Total Transaksi',
                        data: [12, 19, 3, 5, 2, 3, 15], 
                        backgroundColor: 'rgba(105, 108, 255, 0.2)',
                        borderColor: 'rgba(105, 108, 255, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endpush