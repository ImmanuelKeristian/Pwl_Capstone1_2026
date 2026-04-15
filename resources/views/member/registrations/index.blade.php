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
                        <th>Jenis Tiket</th>
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
                        
                        <td>
                            @if(isset($registration->ticket_type))
                                <span class="badge bg-label-dark">{{ strtoupper($registration->ticket_type) }}</span>
                            @else
                                <span class="text-muted">Regular</span>
                            @endif
                        </td>

                        <td>{{ $registration->eventSession->start_datetime->format('d M Y, H:i') }}</td>
                        <td>
                            @php
                                $statusClass = 'secondary'; 
                                if ($registration->status->name == 'Menunggu Pembayaran') $statusClass = 'warning';
                                if ($registration->status->name == 'Menunggu Konfirmasi') $statusClass = 'info';
                                if ($registration->status->name == 'Pembayaran Diterima') $statusClass = 'success';
                                if ($registration->status->name == 'Hadir') $statusClass = 'primary';
                                if ($registration->status->name == 'Dibatalkan' || $registration->status->name == 'Tidak Hadir') $statusClass = 'danger';
                                if ($registration->status->name == 'Waiting List') $statusClass = 'dark';
                            @endphp
                            <span class="badge bg-label-{{ $statusClass }}">{{ $registration->status->name }}</span>
                        </td>
                        <td>
                            <div class="d-flex">
                                @if ($registration->status->name == 'Menunggu Pembayaran')
                                    <a href="{{ route('member.registrations.payment', $registration) }}" class="btn btn-sm btn-primary me-2">Lakukan Pembayaran</a>
                                
                                @elseif ($registration->status->name == 'Waiting List')
                                    <button class="btn btn-sm btn-outline-secondary me-2" disabled>Dalam Antrean</button>

                                @elseif ($registration->status->name == 'Pembayaran Diterima')
                                    <button type="button" class="btn btn-icon btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $registration->id }}" title="Lihat E-Ticket">
                                        <i class="bx bx-qr-scan"></i>
                                    </button>
                                    
                                    {{-- BARU: Tombol Kirim Ulang Email Tiket --}}
                                    <form action="#" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-sm btn-secondary me-2" title="Kirim Ulang ke Email">
                                            <i class="bx bx-envelope"></i>
                                        </button>
                                    </form>

                                    {{-- BARU: Simulasi Validasi Scan (Hanya untuk testing dev) --}}
                                    <form action="#" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-icon btn-sm btn-success" title="Simulasi Scan Panitia">
                                            <i class="bx bx-check-shield"></i>
                                        </button>
                                    </form>

                                @elseif ($registration->status->name == 'Hadir' && $registration->certificate)
                                    <a href="{{ asset('storage/' . $registration->certificate->file_path) }}" class="btn btn-sm btn-success" download>
                                        <i class="bx bx-download me-1"></i> Sertifikat
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Struktur Modal QR Code dipertahankan --}}
                    @if ($registration->status->name == 'Pembayaran Diterima' || $registration->status->name == 'Hadir')
                    <div class="modal fade qr-modal" id="qrModal-{{ $registration->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCenterTitle">E-Ticket & QR Code</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p>Tunjukkan QR Code ini kepada panitia untuk di-scan.</p>
                                    <div class="qr-code-container d-flex justify-content-center" data-qr-content="REG-{{ $registration->user_id }}-{{ $registration->event_session_id }}">
                                    </div>
                                    <p class="mt-3 mb-0"><strong>{{ $registration->user->name }}</strong></p>
                                    <p class="text-muted mb-0">{{ $registration->eventSession->title }}</p>
                                    <span class="badge bg-label-dark mt-2">{{ isset($registration->ticket_type) ? strtoupper($registration->ticket_type) : 'REGULAR' }}</span>
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    {{-- Tombol Download PDF (Membutuhkan DomPDF nantinya) --}}
                                    <a href="#" class="btn btn-outline-danger"><i class="bx bxs-file-pdf me-1"></i> Unduh PDF</a>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr><td colspan="7" class="text-center py-4">Anda belum terdaftar di event manapun. <a href="{{ route('home') }}">Lihat daftar event.</a></td></tr>
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
                            width: 200,
                            height: 200,
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