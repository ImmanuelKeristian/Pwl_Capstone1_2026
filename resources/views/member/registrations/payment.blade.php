@extends('layouts.master')
@section('title', 'Pembayaran Event')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('member.registrations.index') }}">Pendaftaran Saya</a> / 
        </span> Pembayaran
    </h4>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Instruksi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <p>Silakan lakukan pembayaran untuk sesi <strong>"{{ $registration->eventSession->title }}"</strong> sebesar:</p>
                    <h3 class="text-primary">Rp {{ number_format($registration->eventSession->registration_fee, 0, ',', '.') }}</h3>
                    <hr>
                    <p class="mb-2"><strong>Transfer ke rekening berikut:</strong></p>
                    <ul class="list-unstyled">
                        <li><strong>Bank:</strong> Bank ABC</li>
                        <li><strong>No. Rekening:</strong> 123-456-7890</li>
                        <li><strong>Atas Nama:</strong> Universitas Capstone</li>
                    </ul>
                    <p class="mt-3">Setelah melakukan pembayaran, mohon upload bukti transfer pada form di samping.</p>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Upload Bukti Pembayaran</h5></div>
                <div class="card-body">
                    <form action="{{ route('member.registrations.processPayment', $registration) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_file" class="form-label">File Bukti Transfer</label>
                            <input class="form-control @error('payment_file') is-invalid @enderror" type="file" id="payment_file" name="payment_file" required>
                            <div class="form-text">Format: JPG, PNG. Ukuran maksimal 2MB.</div>
                            @error('payment_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bx bx-upload me-1"></i> Upload & Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection