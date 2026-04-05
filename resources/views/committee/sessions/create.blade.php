@extends('layouts.master')
@section('title', 'Tambah Sesi Baru')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('committee.events.show', $event->id) }}">{{ $event->title }}</a> /
        </span> Tambah Sesi Baru
    </h4>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('committee.events.sessions.store', $event->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="title">Judul Sesi</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Workshop Laravel Dasar" required />
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="session_date">Tanggal Sesi</label>
                        <input class="form-control" type="date" id="session_date" name="session_date" value="{{ old('session_date') }}" min="{{ $event->start_date->format('Y-m-d') }}" max="{{ $event->end_date->format('Y-m-d') }}" required />
                        <div class="form-text">Pilih tanggal antara {{ $event->start_date->format('d M Y') }} dan {{ $event->end_date->format('d M Y') }}.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="start_time">Waktu Mulai</label>
                        <input class="form-control" type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="end_time">Waktu Selesai</label>
                        <input class="form-control" type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="location">Lokasi</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Ruang H.5.1" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="speaker">Narasumber</label>
                        <input type="text" class="form-control" id="speaker" name="speaker" value="{{ old('speaker') }}" placeholder="Contoh: Budi Doremi, S.Kom" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="registration_fee">Biaya Registrasi (Rp)</label>
                        <input type="number" class="form-control" id="registration_fee" name="registration_fee" value="{{ old('registration_fee', 0) }}" min="0" step="1000" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="max_participants">Kuota Peserta</label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi Sesi (Opsional)</label>
                    <textarea id="description" class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan Sesi</button>
                <a href="{{ route('committee.events.show', $event->id) }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
