@extends('layouts.master')
@section('title', 'Kelola Sesi')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Panitia /</span> Kelola Sesi</h4>
    <div class="card">
        <h5 class="card-header">Pilih Sesi untuk Absensi atau Kelola Sertifikat</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul Sesi</th>
                        <th>Event Induk</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sessions as $session)
                    <tr>
                        <td><strong>{{ $session->title }}</strong></td>
                        <td>{{ $session->event->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($session->start_datetime)->format('d M Y, H:i') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('committee.attendance.scan', $session) }}" class="btn btn-primary btn-sm me-2">
                                    <i class="bx bx-qr-scan me-1"></i> Scanner
                                </a>

                                <a href="{{ route('committee.certificates.index', $session) }}" class="btn btn-info btn-sm">
                                    <i class="bx bxs-file-pdf me-1"></i> Sertifikat
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada sesi yang dijadwalkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">{{ $sessions->links() }}</div>
    </div>
</div>
@endsection
