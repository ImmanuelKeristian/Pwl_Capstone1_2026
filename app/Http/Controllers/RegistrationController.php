<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventRegister;
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
    public function payment(Request $request, EventRegister $registration) // Ditambahkan 'Request $request'
    {
        // Pastikan member hanya bisa mengakses pendaftarannya sendiri
        if ($registration->user_id !== $request->user()->id) { // Diganti menggunakan $request
            abort(403);
        }

        return view('member.registrations.payment', compact('registration'));
    }

    /**
     * Memproses upload bukti pembayaran.
     */
    public function processPayment(Request $request, EventRegister $registration)
    {
        // Pastikan member hanya bisa memproses pendaftarannya sendiri
        if ($registration->user_id !== $request->user()->id) { // Diganti menggunakan $request
            abort(403);
        }

        $request->validate([
            'payment_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('payment_file')) {
            // Hapus bukti lama jika ada
            if ($registration->payment_file) {
                Storage::disk('public')->delete($registration->payment_file);
            }

            $path = $request->file('payment_file')->store('payment_proofs', 'public');
            $registration->payment_file = $path;
        }
        
        // Ubah status menjadi "Menunggu Konfirmasi" (Asumsi ID 2)
        $registration->status_id = 2; 
        $registration->save();

        return redirect()->route('member.registrations.index')
                         ->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi dari tim keuangan.');
    }
}
