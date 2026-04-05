{{-- Lokasi File: resources/views/member/registrations/index.blade.php --}}
@extends('layouts.master')
@section('title', 'Event Saya')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Area Member /</span> Pendaftaran Saya</h4>
    <div class="card">
        <h5 class="card-header">Riwayat Pendaftaran</h5>
        <div class="table-responsive text-nowrap">
            @if(session('success'))<div class="alert alert-success mx-4">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger mx-4">{{ session('error') }}</div>@endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sesi</th>
                        <th>Event Induk</th>
                        <th>Jadwal Sesi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($registrations as $index => $registration)
                    <tr>
                        <td>{{ $registrations->firstItem() + $index }}</td>
                        <td><strong>{{ $registration->eventSession->title }}</strong></td>
                        <td>{{ $registration->eventSession->event->title }}</td>
                        <td>{{ $registration->eventSession->start_datetime->format('d M Y, H:i') }}</td>
                        <td>
                            @php
                                $statusClass = 'secondary'; // Default
                                if ($registration->status->name == 'Menunggu Pembayaran') $statusClass = 'warning';
                                if ($registration->status->name == 'Menunggu Konfirmasi') $statusClass = 'info';
                                if ($registration->status->name == 'Pembayaran Diterima') $statusClass = 'success';
                                if ($registration->status->name == 'Hadir') $statusClass = 'primary';
                                if ($registration->status->name == 'Dibatalkan' || $registration->status->name == 'Tidak Hadir') $statusClass = 'danger';
                            @endphp
                            <span class="badge bg-label-{{ $statusClass }}">{{ $registration->status->name }}</span>
                        </td>
                        <td>
                            {{-- Logika lengkap untuk tombol Aksi --}}
                            @if ($registration->status->name == 'Menunggu Pembayaran')
                                <a href="{{ route('member.registrations.payment', $registration) }}" class="btn btn-sm btn-primary">Lakukan Pembayaran</a>
                            @elseif ($registration->status->name == 'Pembayaran Diterima')
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $registration->id }}">Lihat Tiket</button>
                            @elseif ($registration->status->name == 'Hadir' && $registration->certificate)
                                <a href="{{ asset('storage/' . $registration->certificate->file_path) }}" class="btn btn-sm btn-success" download>
                                    <i class="bx bx-download me-1"></i> Download Sertifikat
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    {{-- Struktur Modal untuk QR Code (hanya jika pembayaran sudah diterima) --}}
                    @if ($registration->status->name == 'Pembayaran Diterima' || $registration->status->name == 'Hadir')
                    <div class="modal fade qr-modal" id="qrModal-{{ $registration->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCenterTitle">Tiket QR Code</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p>Tunjukkan QR Code ini kepada panitia saat registrasi ulang.</p>
                                    <div class="qr-code-container d-flex justify-content-center" data-qr-content="REG-{{ $registration->user_id }}-{{ $registration->event_session_id }}">
                                        {{-- QR Code akan dibuat di sini oleh JavaScript --}}
                                    </div>
                                    <p class="mt-3 mb-0"><strong>{{ $registration->user->name }}</strong></p>
                                    <p class="text-muted">{{ $registration->eventSession->title }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr><td colspan="6" class="text-center py-4">Anda belum terdaftar di event manapun. <a href="{{ route('home') }}">Lihat daftar event.</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">{{ $registrations->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrModals = document.querySelectorAll('.qr-modal');
        qrModals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function (event) {
                const qrContainer = this.querySelector('.qr-code-container');
                if (qrContainer.innerHTML.trim() === '') {
                    const content = qrContainer.getAttribute('data-qr-content');
                    if (content) {
                        new QRCode(qrContainer, {
                            text: content,
                            width: 256,
                            height: 256,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    }
                }
            });
        });
    });
</script>
@endpush
