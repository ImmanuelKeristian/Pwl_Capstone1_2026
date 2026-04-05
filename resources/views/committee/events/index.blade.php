@extends('layouts.master')
@section('title', 'Kelola Event - Panitia')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Panitia /</span> Kelola Event
    </h4>

    <div class="mb-3">
        <a href="{{ route('committee.events.create') }}" class="btn btn-primary">
            <span class="tf-icons bx bx-plus-circle"></span>&nbsp; Tambah Event Induk Baru
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Daftar Event Induk</h5>
        <div class="table-responsive text-nowrap">
            @if(session('success'))
                <div class="alert alert-success mx-4" role="alert">{{ session('success') }}</div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Event</th>
                        <th>Kategori</th>
                        <th>Periode Event</th>
                        <th>Jumlah Sesi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($events as $index => $event)
                        <tr>
                            <td>{{ $events->firstItem() + $index }}</td>
                            <td><strong>{{ Str::limit($event->title, 40) }}</strong></td>
                            <td><span class="badge bg-label-primary">{{ $event->eventCategory->name ?? 'N/A' }}</span></td>
                            <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
                            <td>{{ $event->sessions->count() }}</td>
                            <td>
                                <div class="d-flex">
                                    {{-- Tombol untuk melihat detail dan mengelola sesi --}}
                                    <a class="btn btn-icon btn-sm btn-info me-2" href="{{ route('committee.events.show', $event->id) }}" data-bs-toggle="tooltip" title="Lihat & Kelola Sesi">
                                        <i class="bx bx-list-ul"></i>
                                    </a>
                                    
                                    {{-- Tombol untuk mengedit event induk --}}
                                    <a class="btn btn-icon btn-sm btn-warning me-2" href="{{ route('committee.events.edit', $event->id) }}" data-bs-toggle="tooltip" title="Edit Event Induk">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>

                                    <form action="{{ route('committee.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh event ini beserta semua sesinya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Event Induk"><i class="bx bx-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada event yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">{{ $events->links() }}</div>
    </div>
</div>
@endsection