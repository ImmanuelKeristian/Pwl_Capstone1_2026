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

            <table class="table table-hover">
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
                            <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}</td>
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

                                    {{-- Tombol Pemicu Modal Hapus --}}
                                    <button type="button" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteEvent{{ $event->id }}" title="Hapus Event Induk">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalDeleteEvent{{ $event->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title text-danger fw-bold"><i class="bx bx-error-circle me-1"></i> Peringatan Penghapusan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus event <strong>"{{ $event->title }}"</strong> secara permanen?</p>
                                        <div class="alert alert-warning mb-0">
                                            <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-error me-1"></i> Perhatian!</h6>
                                            <span>Menghapus event ini juga akan menghapus <strong>seluruh sesi</strong> yang ada di dalamnya. Tindakan ini tidak dapat dibatalkan.</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('committee.events.destroy', $event->id) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ya, Hapus Event</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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