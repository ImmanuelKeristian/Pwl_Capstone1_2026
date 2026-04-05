@extends('layouts.master')
@section('title', 'Upload Sertifikat')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Panitia /</span> Upload Sertifikat untuk {{ $session->title }}</h4>
    <div class="card">
        <h5 class="card-header">Daftar Peserta Hadir</h5>
        <div class="table-responsive text-nowrap">
            @if(session('success'))<div class="alert alert-success mx-4">{{ session('success') }}</div>@endif
            <table class="table">
                <thead><tr><th>Nama Peserta</th><th>Email</th><th>Status Sertifikat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($attendees as $attendee)
                    <tr>
                        <td><strong>{{ $attendee->user->name }}</strong></td>
                        <td>{{ $attendee->user->email }}</td>
                        <td>
                            @if($attendee->certificate)
                                <span class="badge bg-label-success">Sudah Diupload</span>
                            @else
                                <span class="badge bg-label-warning">Belum Ada</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('committee.certificates.upload', $attendee) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="file" name="certificate_file" class="form-control" required accept=".pdf">
                                    <button class="btn btn-outline-primary" type="submit">Upload</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Belum ada peserta yang hadir di sesi ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">{{ $attendees->links() }}</div>
    </div>
</div>
@endsection
