{{-- Lokasi File: resources/views/auth/login.blade.php --}}
@extends('layouts.master')
@section('title', 'Login')

@section('web-content')
<div class="container-xxl">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 400px;">
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-2 fw-bold text-center">Selamat Datang!</h4>
                    <p class="mb-4 text-muted text-center">Silakan masuk ke akun Anda</p>

                    @if (session('status'))
                        <div class="alert alert-success mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" autofocus required />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        <small>Lupa Password?</small>
                                    </a>
                                @endif
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                                <label class="form-check-label" for="remember_me"> Ingat Saya </label>
                            </div>
                        </div>

                        <div class="mb-3 mt-4">
                            <button class="btn btn-primary d-grid w-100" type="submit">Log in</button>
                        </div>
                    </form>

                    <p class="text-center mb-0 mt-3">
                        <span class="text-muted">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="text-decoration-none">
                            <span>Daftar di sini</span>
                        </a>
                    </p>
                </div>
            </div>
            </div>
    </div>
</div>
@endsection