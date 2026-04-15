{{-- Lokasi File: resources/views/member/registrations/payment.blade.php --}}
@extends('layouts.master')
@section('title', 'Pembayaran Event')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('member.registrations.index') }}" class="text-decoration-none text-muted">Pendaftaran Saya</a> / 
        </span> Pembayaran
    </h4>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bx bx-info-circle me-2"></i>Instruksi Pembayaran</h5>
                    {{-- Menampilkan badge status --}}
                    <span class="badge bg-label-{{ $registration->status->name == 'Menunggu Pembayaran' ? 'warning' : 'info' }}">
                        {{ $registration->status->name }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="mb-1">Silakan lakukan pembayaran untuk sesi event:</p>
                    <h5 class="fw-bold text-dark mb-4">{{ $registration->eventSession->title }}</h5>
                    
                    <div class="alert alert-primary d-flex align-items-center mb-4 p-3" role="alert">
                        <i class="bx bx-wallet bx-md me-3"></i>
                        <div>
                            <span class="d-block text-muted small mb-1">Total yang harus dibayar</span>
                            <h3 class="mb-0 text-primary fw-bold">Rp {{ number_format($registration->eventSession->registration_fee, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    <p class="mb-2 fw-semibold">Transfer ke rekening berikut:</p>
                    <ul class="list-group list-group-flush mb-4 border rounded">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bx bxs-bank text-muted me-2"></i><strong>Bank ABC</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-credit-card text-muted me-2"></i>No. Rekening</span>
                            <span class="fw-bold user-select-all">123-456-7890</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bx bx-user text-muted me-2"></i>Atas Nama</span>
                            <span class="fw-bold">Event Event</span>
                        </li>
                    </ul>

                    <div class="alert alert-warning mb-0" role="alert">
                        <div class="d-flex">
                            <i class="bx bx-time-five me-2 mt-1"></i> 
                            <span>Setelah melakukan pembayaran, mohon upload bukti transfer pada form di samping. Proses verifikasi biasanya memakan waktu maksimal 1x24 jam kerja.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bx bx-upload me-2"></i>Upload Bukti Transfer</h5>
                </div>
                <div class="card-body">
                    
                    {{-- JIKA SUDAH UPLOAD (Menunggu Konfirmasi) --}}
                    @if($registration->status->name == 'Menunggu Konfirmasi')
                        <div class="text-center mt-3">
                            <i class="bx bx-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 fw-bold">Bukti Terkirim</h5>
                            <p class="text-muted small mb-4">Admin sedang memverifikasi pembayaran Anda. Mohon tunggu konfirmasi selanjutnya.</p>
                            
                            @if($registration->payment_file)
                                <div class="border rounded p-2 text-start">
                                    <p class="mb-2 small text-muted fw-semibold">File yang diupload:</p>
                                    <img src="{{ asset('storage/' . $registration->payment_file) }}" alt="Bukti Pembayaran" class="img-fluid rounded w-100" style="max-height: 250px; object-fit: contain;">
                                </div>
                            @endif
                        </div>

                    {{-- JIKA BELUM UPLOAD (Menunggu Pembayaran) --}}
                    @else
                        <form action="{{ route('member.registrations.processPayment', $registration) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4 text-center">
                                <img id="imagePreview" src="https://placehold.co/400x300/f5f5f9/a1acb8?text=Preview+Bukti+Transfer" alt="Preview Bukti Transfer" class="img-fluid rounded border" style="max-height: 220px; width: 100%; object-fit: cover; display: block;">
                            </div>

                            <div class="mb-4">
                                <label for="payment_file" class="form-label fw-semibold">Pilih File Bukti Transfer <span class="text-danger">*</span></label>
                                <input class="form-control @error('payment_file') is-invalid @enderror" type="file" id="payment_file" name="payment_file" accept="image/jpeg, image/png, image/jpg" required onchange="previewImage(this)">
                                <div class="form-text">Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB.</div>
                                @error('payment_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="bx bx-check-shield me-1"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.objectFit = 'contain';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            // Kembali ke placeholder jika file dibatalkan
            preview.src = "https://placehold.co/400x300/f5f5f9/a1acb8?text=Preview+Bukti+Transfer";
            preview.style.objectFit = 'cover';
        }
    }
</script>
@endpush