{{-- Lokasi File: resources/views/auth/verify-email.blade.php --}}
@extends('layouts.master')
@section('title', 'Verifikasi Email')

@section('web-content')
<div class="container-xxl">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 450px;">
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bx bx-envelope bx-lg text-primary mb-3"></i>
                        <h4 class="mb-2 fw-bold">Verifikasi Email Anda</h4>
                    </div>
                    
                    <p class="text-muted text-center mb-4">
                        Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami akan mengirimkan ulang.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mb-4 text-center" role="alert">
                            Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                        </div>
                    @endif

                    <div class="d-flex flex-column gap-3 mt-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary d-grid w-100">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}" class="text-center">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted text-decoration-none">
                                Log Out
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            </div>
    </div>
</div>
@endsection