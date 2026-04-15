{{-- Lokasi File: resources/views/auth/confirm-password.blade.php --}}
@extends('layouts.master')
@section('title', 'Konfirmasi Password')

@section('web-content')
<div class="container-xxl">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 400px;">
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bx bx-shield-quarter bx-lg text-warning mb-3"></i>
                        <h4 class="mb-2 fw-bold">Area Terbatas</h4>
                    </div>
                    
                    <p class="text-muted text-center mb-4">
                        Ini adalah area aman dari aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.
                    </p>

                    <form id="formAuthentication" action="{{ route('password.confirm') }}" method="POST">
                        @csrf

                        <div class="mb-4 form-password-toggle">
                            <label class="form-label" for="password">Password Anda</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required autocomplete="current-password" autofocus />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">Batal</a>
                            <button class="btn btn-primary" type="submit">Konfirmasi</button>
                        </div>
                    </form>

                </div>
            </div>
            </div>
    </div>
</div>
@endsection