@extends('layouts.master')
@section('title', 'Detail Event: ' . $event->title)

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light">Kelola Event /</span> Detail Event
    </h4>

    {{-- Info Event Induk --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3>{{ $event->title }}</h3>
                    <p class="mb-1"><strong>Kategori:</strong> {{ $event->eventCategory->name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Periode:</strong> {{ $event->start_date->format('d M Y') }} s/d {{ $event->end_date->format('d M Y') }}</p>
                </div>
                <a href="{{ route('committee.events.edit', $event->id) }}" class="btn btn-sm btn-outline-warning">Edit Event Induk</a>
            </div>
        </div>
    </div>

    {{-- Daftar Sesi --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Sesi</h5>
            <a href="{{ route('committee.events.sessions.create', $event->id) }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Sesi Baru
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            @if(session('success'))
                <div class="alert alert-success mx-4">{{ session('success') }}</div>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul Sesi</th>
                        <th>Jadwal</th>
                        <th>Lokasi</th>
                        <th>Narasumber</th>
                        <th>Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($event->sessions as $session)
                    <tr>
                        <td><strong>{{ $session->title }}</strong></td>
                        <td>{{ $session->session_date->format('d M Y') }}, {{ date('H:i', strtotime($session->start_time)) }} - {{ date('H:i', strtotime($session->end_time)) }}</td>
                        <td>{{ $session->location }}</td>
                        <td>{{ $session->speaker }}</td>
                        <td><span class="badge bg-label-info">{{ $session->eventRegisters->count() }} / {{ $session->max_participants }}</span></td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('committee.sessions.edit', $session->id) }}" class="btn btn-icon btn-sm btn-warning me-2"><i class="bx bx-edit-alt"></i></a>
                                <form action="{{ route('committee.sessions.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus sesi ini? Ini akan menghapus semua pendaftar di dalamnya.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada sesi untuk event ini. Silakan tambahkan sesi baru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
