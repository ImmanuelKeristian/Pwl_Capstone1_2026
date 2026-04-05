{{-- resources/views/events/show-public.blade.php --}}
@extends('layouts.master')

@section('title', $event->title)

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <a href="{{ route('events.guest.index') }}" class="text-muted fw-light">Event /</a> Detail Event
    </h4>

    {{-- BAGIAN DETAIL EVENT INDUK --}}
    <div class="card mb-4">
        <div class="row g-0">
            <div class="col-md-4">
                @if($event->poster_kegiatan)
                    <img class="card-img card-img-left" src="{{ asset('storage/' . $event->poster_kegiatan) }}" alt="Poster {{ $event->title }}" style="object-fit: cover; height: 100%;">
                @else
                    <img class="card-img card-img-left" src="https://placehold.co/400x600/EFEFEF/AAAAAA?text=Event%20Poster" alt="Poster Default">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    @if($event->eventCategory)
                        <span class="badge bg-label-primary mb-2">{{ $event->eventCategory->name }}</span>
                    @endif
                    <h3 class="card-title">{{ $event->title }}</h3>
                    <p class="card-text">{!! nl2br(e($event->description)) !!}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN DAFTAR SESI EVENT --}}
    <h5 class="py-3 mb-2">Jadwal Sesi yang Tersedia</h5>

    @if($event->sessions->isEmpty())
        <div class="alert alert-warning">Belum ada sesi yang tersedia untuk event ini.</div>
    @else
        <div class="row">
            @foreach($event->sessions as $session)
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between">
                            {{-- Info Sesi --}}
                            <div class="flex-grow-1 mb-3 mb-md-0">
                                <h5 class="card-title">{{ $session->title }}</h5>
                                <p class="card-text mb-1"><i class="bx bx-calendar bx-xs me-1"></i> {{ $session->session_date->translatedFormat('l, d F Y') }}</p>
                                <p class="card-text mb-1"><i class="bx bx-time-five bx-xs me-1"></i> {{ date('H:i', strtotime($session->start_time)) }} - {{ date('H:i', strtotime($session->end_time)) }} WIB</p>
                                @if($session->location)<p class="card-text mb-1"><i class="bx bx-map bx-xs me-1"></i> {{ $session->location }}</p>@endif
                                @if($session->speaker)<p class="card-text mb-0"><i class="bx bx-user-voice bx-xs me-1"></i> {{ $session->speaker }}</p>@endif
                            </div>
                            
                            {{-- Info Kuota & Tombol Aksi --}}
                            <div class="text-md-end">
                                @php
                                    $kuotaTerisi = $session->eventRegistrations->count();
                                    $sisaKuota = $session->max_participants - $kuotaTerisi;
                                    $isFull = $sisaKuota <= 0;
                                @endphp
                                <p class="mb-2">Kuota: <span class="fw-semibold">{{ $kuotaTerisi }}/{{ $session->max_participants }}</span></p>
                                
                                {{-- PERUBAHAN LOGIKA TOMBOL DIMULAI DI SINI --}}
                                @guest
                                    <a href="{{ route('login') }}?redirect_to_session={{ $session->id }}" class="btn btn-primary">Login untuk Daftar</a>
                                @endguest

                                @auth
                                    @if(auth()->user()->role == 'member')
                                        @php
                                            $isRegistered = auth()->user()->eventRegistrations()->where('event_session_id', $session->id)->exists();
                                        @endphp
                                        
                                        @if($isRegistered)
                                            <button class="btn btn-success" disabled><i class="bx bx-check-circle me-1"></i> Sudah Terdaftar</button>
                                        @elseif(now()->isAfter($session->start_datetime))
                                            <button class="btn btn-secondary" disabled>Sesi Telah Dimulai</button>
                                        @elseif($isFull)
                                            <button class="btn btn-secondary" disabled>Kuota Penuh</button>
                                        @else
                                            <form action="{{ route('sessions.register', $session->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-primary w-100" type="submit">Daftar Sesi Ini</button>
                                            </form>
                                        @endif
                                    @else
                                        <button class="btn btn-secondary" disabled>Login sebagai Member</button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
