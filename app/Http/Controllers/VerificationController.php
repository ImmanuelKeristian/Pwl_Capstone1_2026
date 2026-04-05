<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventRegister;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Menampilkan semua pendaftaran yang statusnya "Menunggu Konfirmasi".
     */
    public function index()
    {
        $pendingRegistrations = EventRegister::with('user', 'eventSession.event')
                                            ->where('status_id', 2) // Asumsi status ID 2 adalah "Menunggu Konfirmasi"
                                            ->latest()
                                            ->paginate(15);

        return view('finance.verifications.index', compact('pendingRegistrations'));
    }

    /**
     * Menyetujui pendaftaran.
     */
    public function approve(EventRegister $registration)
    {
        // Ubah status menjadi "Pembayaran Diterima" (Asumsi ID 3)
        $registration->update(['status_id' => 3]);
        
        // Di sini Anda bisa menambahkan logika untuk mengirim notifikasi email ke member
        // Mail::to($registration->user->email)->send(new PaymentApproved($registration));

        return redirect()->route('finance.verifications.index')
                         ->with('success', 'Pembayaran untuk ' . $registration->user->name . ' telah disetujui.');
    }

    /**
     * Menolak pendaftaran.
     */
    public function reject(Request $request, EventRegister $registration)
    {
        // Di sini Anda bisa menambahkan logika untuk menyimpan alasan penolakan
        // $rejectionReason = $request->input('rejection_reason');

        // Kembalikan status ke "Menunggu Pembayaran" agar member bisa upload ulang
        $registration->update(['status_id' => 1]);

        // Kirim notifikasi ke member bahwa pembayaran ditolak beserta alasannya
        // Mail::to($registration->user->email)->send(new PaymentRejected($registration, $rejectionReason));
        
        return redirect()->route('finance.verifications.index')
                         ->with('success', 'Pembayaran untuk ' . $registration->user->name . ' telah ditolak.');
    }
}