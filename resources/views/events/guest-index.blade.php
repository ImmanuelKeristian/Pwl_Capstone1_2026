{{-- resources/views/events/guest-index.blade.php --}}
@extends('layouts.master')

@section('title', 'Daftar Event Universitas')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><span class="text-muted fw-light">Event /</span> Daftar Event</h4>
        
        {{-- Form Filter Kategori (Opsional) --}}
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

    @if(!isset($events) || $events->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
          Tidak ada Event yang sedang berlangsung!
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($events as $event)
            <div class="col">
                <a href="{{ route('events.show.public', $event->id) }}" class="card-link">
                    <div class="card h-100 shadow-sm card-event">
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
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            
                            @if($event->eventCategory)
                            <span class="badge bg-label-primary mb-2">{{ $event->eventCategory->name }}</span>
                            @endif

                            <p class="card-text text-muted small mb-1">
                                <i class="bx bx-calendar bx-xs me-1"></i> 
                                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                            </p>

                            @if($event->lokasi)
                            <p class="card-text text-muted small mb-2">
                                <i class="bx bx-map bx-xs me-1"></i> {{ $event->lokasi }}
                            </p>
                            @endif
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
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card-link {
        text-decoration: none;
        color: inherit;
    }
    .card-event {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-event:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush