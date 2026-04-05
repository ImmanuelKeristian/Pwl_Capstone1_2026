{{-- resources/views/admin/event-categories/index.blade.php --}}
@extends('layouts.master')
@section('title', 'Kelola Kategori Event')

@section('web-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Kelola Kategori Event</h4>
    <a href="{{ route('admin.event-categories.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>
    <div class="card">
        <h5 class="card-header">Daftar Kategori Event</h5>
        <div class="table-responsive text-nowrap">
            @if(session('success'))
            <div class="alert alert-success mx-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger mx-4">{{ session('error') }}</div>
            @endif
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Event</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($categories as $index => $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $index }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><span class="badge bg-label-info">{{ $category->events_count ?? $category->events->count() }}</span></td>
                        <td>
                            <div class="d-flex">
                                <a class="btn btn-icon btn-sm btn-warning me-2" href="{{ route('admin.event-categories.edit', $category->id) }}"><i class="bx bx-edit-alt"></i></a>
                                <form action="{{ route('admin.event-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 mx-4">{{ $categories->links() }}</div>
    </div>
</div>
@endsection