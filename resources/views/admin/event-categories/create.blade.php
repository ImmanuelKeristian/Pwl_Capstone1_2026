@extends('layouts.master')
@section('title', 'Tambah Kategori Event')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kategori Event /</span> Tambah</h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.event-categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.event-categories.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection