@extends('layouts.master')

@section('title', 'Tambah Event Induk Baru')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Kelola Event /</span> Tambah Event Induk
    </h4>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Formulir Event Induk</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('committee.events.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="title">Judul Event Utama</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Pekan IT Maranatha 2025" value="{{ old('title') }}" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi Umum Event</label>
                            <textarea id="description" class="form-control" name="description" rows="5" placeholder="Jelaskan tentang event besar ini secara umum..." required>{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_category_id" class="form-label">Kategori Event</label>
                                <select id="event_category_id" class="form-select" name="event_category_id" required>
                                    <option value="">Pilih Kategori...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('event_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="poster_kegiatan" class="form-label">Poster Utama</label>
                                <input class="form-control" type="file" id="poster_kegiatan" name="poster_kegiatan" accept="image/*">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="start_date">Tanggal Mulai Event</label>
                                <input class="form-control" type="date" value="{{ old('start_date') }}" id="start_date" name="start_date" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="end_date">Tanggal Berakhir Event</label>
                                <input class="form-control" type="date" value="{{ old('end_date') }}" id="end_date" name="end_date" required />
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Simpan dan Lanjut Tambah Sesi</button>
                            <a href="{{ route('committee.events.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
