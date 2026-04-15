{{-- Lokasi File: resources/views/profile/edit.blade.php --}}
@extends('layouts.master')
@section('title', 'Profil Saya')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pengaturan /</span> Profil Saya
    </h4>

    <div class="row">
        <div class="col-md-8 col-12">
            
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom mb-3">
                    <h5 class="mb-0">Informasi Profil</h5>
                    <small class="text-muted">Perbarui informasi profil dan alamat email akun Anda.</small>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header border-bottom mb-3">
                    <h5 class="mb-0">Ubah Password</h5>
                    <small class="text-muted">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</small>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card shadow-sm border-danger">
                <div class="card-header border-bottom border-danger mb-3">
                    <h5 class="mb-0 text-danger">Hapus Akun</h5>
                    <small class="text-danger">Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.</small>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection