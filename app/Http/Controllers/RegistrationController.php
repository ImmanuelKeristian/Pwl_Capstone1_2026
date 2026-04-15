<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventRegister;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * Menampilkan daftar semua pendaftaran milik pengguna yang login.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $registrations = EventRegister::with('eventSession.event', 'status')
                                        ->where('user_id', $user->id)
                                        ->latest('created_at')
                                        ->paginate(10);

        return view('member.registrations.index', compact('registrations'));
    }

    /**
     * Menampilkan halaman untuk melakukan pembayaran dan upload bukti.
     */
    public function payment(Request $request, EventRegister $registration)
    {
        // Pastikan member hanya bisa mengakses pendaftarannya sendiri
        if ($registration->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('member.registrations.payment', compact('registration'));
    }

    /**
     * Memproses upload bukti pembayaran.
     */
    public function processPayment(Request $request, EventRegister $registration)
    {
        // Pastikan member hanya bisa memproses pendaftarannya sendiri
        if ($registration->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        // Proteksi tambahan: Cegah upload jika status sudah Lunas, Dibatalkan, atau Hadir
        $currentStatus = $registration->status->name;
        if ($currentStatus !== 'Menunggu Pembayaran' && $currentStatus !== 'Menunggu Konfirmasi') {
            return redirect()->route('member.registrations.index')
                             ->with('error', 'Status pendaftaran Anda saat ini tidak mengizinkan upload bukti pembayaran.');
        }

        // Validasi input dengan pesan error kustom bahasa Indonesia
        $request->validate([
            'payment_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'payment_file.required' => 'File bukti pembayaran wajib diunggah.',
            'payment_file.image'    => 'File harus berupa gambar.',
            'payment_file.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'payment_file.max'      => 'Ukuran file maksimal adalah 2MB.',
        ]);

        if ($request->hasFile('payment_file')) {
            // Hapus bukti lama jika ada (berguna jika user diminta re-upload)
            if ($registration->payment_file) {
                Storage::disk('public')->delete($registration->payment_file);
            }

            $path = $request->file('payment_file')->store('payment_proofs', 'public');
            $registration->payment_file = $path;
        }
        
        // Praktik Terbaik: Cari ID status berdasarkan nama, bukan hardcode ID angka "2".
        // Ini mencegah error jika urutan ID di database berubah di masa depan.
        $statusMenunggu = Status::where('name', 'Menunggu Konfirmasi')->first();
        if ($statusMenunggu) {
            $registration->status_id = $statusMenunggu->id;
        } else {
            // Fallback aman jika query di atas gagal
            $registration->status_id = 2; 
        }
        
        $registration->save();

        // Menggunakan back() agar user langsung melihat tampilan "Bukti Terkirim" beserta gambarnya
        return back()->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi dari tim keuangan.');
    }
}