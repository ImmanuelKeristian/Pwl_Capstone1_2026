@extends('layouts.master')
@section('title', 'Edit Sesi')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('committee.events.show', $session->event->id) }}">{{ $session->event->title }}</a> /
        </span> Edit Sesi
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('committee.sessions.update', $session->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Sesi</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $session->title) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="session_date">Tanggal Sesi</label>
                        <input class="form-control" type="date" id="session_date" name="session_date" value="{{ old('session_date', $session->session_date->format('Y-m-d')) }}" min="{{ $session->event->start_date->format('Y-m-d') }}" max="{{ $session->event->end_date->format('Y-m-d') }}" required />
                    </div>
                     <div class="col-md-4 mb-3">
                        <label class="form-label" for="start_time">Waktu Mulai</label>
                        <input class="form-control" type="time" id="start_time" name="start_time" value="{{ old('start_time', $session->start_time) }}" required />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="end_time">Waktu Selesai</label>
                        <input class="form-control" type="time" id="end_time" name="end_time" value="{{ old('end_time', $session->end_time) }}" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Sesi</button>
            </form>
        </div>
    </div>
</div>
@endsection