{{-- Lokasi File: resources/views/admin/users/index.blade.php --}}
@extends('layouts.master')
@section('title', 'Kelola Pengguna')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Kelola Pengguna</h4>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pengguna</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Pengguna
            </a>
        </div>
        
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
                                
                                {{-- Percabangan Tombol Hapus Berdasarkan Role --}}
                                @if($user->role === 'administrator')
                                    {{-- Tombol Hapus Terkunci (Untuk Admin) --}}
                                    <button type="button" class="btn btn-icon btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalBlockAdmin{{ $user->id }}" title="Tidak dapat menghapus admin">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                @else
                                    {{-- Tombol Hapus Normal --}}
                                    <button type="button" class="btn btn-icon btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteUser{{ $user->id }}" title="Hapus Pengguna">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @if($user->role === 'administrator')
                    <div class="modal fade" id="modalBlockAdmin{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="modal-title text-warning fw-bold"><i class="bx bx-shield-x me-1"></i> Tindakan Dilarang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center pt-4">
                                    <i class="bx bx-user-x text-warning mb-3" style="font-size: 4rem;"></i>
                                    <h5 class="fw-bold">Akses Ditolak</h5>
                                    <p class="mb-0">Anda tidak dapat menghapus akun <strong>{{ $user->name }}</strong> karena akun ini memiliki hak akses level <strong>Administrator</strong>. Ini dilakukan untuk mencegah Anda terkunci dari sistem (System Lockout).</p>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-center">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="modal fade" id="modalDeleteUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="modal-title text-danger fw-bold"><i class="bx bx-error-circle me-1"></i> Konfirmasi Penghapusan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus akun pengguna <strong>"{{ $user->name }}"</strong>?</p>
                                    <div class="alert alert-danger mb-0">
                                        <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-error me-1"></i> Perhatian!</h6>
                                        <span>Menghapus pengguna ini akan menghapus <strong>seluruh data terkait</strong> seperti riwayat pendaftaran event dan pembayaran. Tindakan ini tidak dapat dibatalkan!</span>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus Pengguna</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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