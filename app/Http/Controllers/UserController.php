<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; 

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Ambil semua pengguna KECUALI admin yang sedang login untuk mencegah
        // admin mengunci atau menghapus akunnya sendiri.
        $users = User::where('id', '!=', Auth::id())->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk mengedit peran pengguna.
     */
    public function edit(User $user)
    {
        // Mencegah admin mengedit akunnya sendiri melalui URL
        if ($user->id === Auth::id()) {
            abort(403, 'Anda tidak dapat mengedit akun Anda sendiri dari halaman ini.');
        }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui peran pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        // Validasi bahwa peran yang dipilih adalah salah satu dari yang diizinkan
        $validated = $request->validate([
            'role' => ['required', Rule::in(['member', 'panitia_kegiatan', 'tim_keuangan', 'administrator'])],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Peran untuk pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Menghapus (atau menonaktifkan) pengguna.
     */
    public function destroy(User $user)
    {
        // Mencegah admin menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // PENTING: Disarankan untuk tidak menghapus permanen data pengguna.
        // Opsi yang lebih baik adalah menambahkan kolom 'is_active' (boolean)
        // dan menonaktifkannya: $user->update(['is_active' => false]);
        // Namun, untuk saat ini kita akan menghapusnya.
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
