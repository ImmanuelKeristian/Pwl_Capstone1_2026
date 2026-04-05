@extends('layouts.master')
@section('title', 'Verifikasi Pembayaran')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tim Keuangan /</span> Verifikasi Pembayaran</h4>

    <div class="card">
        <h5 class="card-header">Pendaftaran Menunggu Konfirmasi</h5>
        <div class="table-responsive text-nowrap">
            @if(session('success'))
                <div class="alert alert-success mx-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mx-4">{{ session('error') }}</div>
            @endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pendaftar</th>
                        <th>Sesi Event</th>
                        <th>Bukti Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($pendingRegistrations as $index => $registration)
                    <tr>
                        <td>{{ $pendingRegistrations->firstItem() + $index }}</td>
                        <td><strong>{{ $registration->user->name }}</strong><br><small>{{ $registration->user->email }}</small></td>
                        <td>{{ $registration->eventSession->title }}</td>
                        <td>
                            @if($registration->payment_file)
                                <a href="{{ asset('storage/' . $registration->payment_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat Bukti</a>
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex">
                                <form action="{{ route('finance.verifications.approve', $registration) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('finance.verifications.reject', $registration) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pembayaran ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada pembayaran yang perlu diverifikasi saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">
            {{ $pendingRegistrations->links() }}
        </div>
    </div>
</div>
@endsection