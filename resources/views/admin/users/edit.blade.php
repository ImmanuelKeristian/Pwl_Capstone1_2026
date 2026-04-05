@extends('layouts.master')
@section('title', 'Edit Peran Pengguna')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="{{ route('admin.users.index') }}">Kelola Pengguna</a> /</span> Edit Peran</h4>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Ubah Peran untuk: {{ $user->name }}</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
            @endif
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly disabled />
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="{{ $user->email }}" readonly disabled />
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Peran (Role)</label>
                    <select id="role" class="form-select" name="role">
                        <option value="member" {{ $user->role == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="panitia_kegiatan" {{ $user->role == 'panitia_kegiatan' ? 'selected' : '' }}>Panitia Kegiatan</option>
                        <option value="tim_keuangan" {{ $user->role == 'tim_keuangan' ? 'selected' : '' }}>Tim Keuangan</option>
                        <option value="administrator" {{ $user->role == 'administrator' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Peran</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
