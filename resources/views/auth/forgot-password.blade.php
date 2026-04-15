{{-- Lokasi File: resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.master')
@section('title', 'Lupa Password')

@section('web-content')
<div class="container-xxl">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 400px;">
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-2 fw-bold text-center">Lupa Password?</h4>
                    <p class="mb-4 text-muted text-center">
                        Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success mb-3 text-center" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="formAuthentication" class="mb-3" action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary d-grid w-100" type="submit">
                            Kirim Tautan Reset Password
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center text-decoration-none">
                            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm me-1"></i>
                            Kembali ke Login
                        </a>
                    </div>

                </div>
            </div>
            </div>
    </div>
</div>
@endsection