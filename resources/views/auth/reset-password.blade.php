{{-- Lokasi File: resources/views/auth/reset-password.blade.php --}}
@extends('layouts.master')
@section('title', 'Atur Ulang Password')

@section('web-content')
<div class="container-xxl">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 400px;">
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-2 fw-bold text-center">Atur Ulang Password</h4>
                    <p class="mb-4 text-muted text-center">Silakan masukkan password baru untuk akun Anda</p>

                    <form id="formAuthentication" action="{{ route('password.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-password-toggle">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password_confirmation" required autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary d-grid w-100" type="submit">Simpan Password Baru</button>
                    </form>
                </div>
            </div>
            </div>
    </div>
</div>
@endsection