{{-- Lokasi File: resources/views/admin/users/index.blade.php --}}
@extends('layouts.master')
@section('title', 'Kelola Pengguna')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Kelola Pengguna</h4>
    <div class="card">
        <h5 class="card-header">Daftar Pengguna</h5>
        <div class="table-responsive text-nowrap">
            @if(session('success'))
                <div class="alert alert-success mx-4" role="alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mx-4" role="alert">{{ session('error') }}</div>
            @endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Terdaftar Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleClass = 'secondary';
                                if($user->role == 'administrator') $roleClass = 'danger';
                                if($user->role == 'panitia_kegiatan') $roleClass = 'info';
                                if($user->role == 'tim_keuangan') $roleClass = 'warning';
                                if($user->role == 'member') $roleClass = 'success';
                            @endphp
                            <span class="badge bg-label-{{ $roleClass }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex">
                                <a class="btn btn-icon btn-sm btn-warning me-2" href="{{ route('admin.users.edit', $user->id) }}" data-bs-toggle="tooltip" title="Edit Peran">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus pengguna akan menghapus semua data terkait (pendaftaran, dll.). Yakin ingin melanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus Pengguna"><i class="bx bx-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pengguna untuk dikelola.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
