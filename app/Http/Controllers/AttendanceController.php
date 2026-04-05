<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventSession;
use App\Models\EventRegister;
use App\Models\EventAttendanceLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $sessions = EventSession::with('event')
                                ->where('session_date', '>=', now()->subDay()) // Tampilkan sesi dari kemarin, hari ini, dan ke depan
                                ->orderBy('session_date', 'desc')
                                ->paginate(15);
        return view('committee.attendance.index', compact('sessions'));
    }
    
    public function scan(EventSession $session)
    {
        return view('committee.attendance.scan', compact('session'));
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'session_id' => 'required|integer|exists:event_sessions,id',
        ]);
    
        // Memecah data dari QR Code. Asumsi formatnya: "REG-USERID-SESSIONID"
        $qrParts = explode('-', $request->qr_code);
        if(count($qrParts) !== 3 || $qrParts[0] !== 'REG') {
            return response()->json(['success' => false, 'message' => 'Format QR Code tidak valid.']);
        }
        $userId = $qrParts[1];
        $sessionIdFromQr = $qrParts[2];
    
        // Validasi tambahan: pastikan sesi di QR code sama dengan sesi yang sedang discan
        if ($sessionIdFromQr != $request->session_id) {
            return response()->json(['success' => false, 'message' => 'Tiket ini bukan untuk sesi ini.']);
        }
    
        $registration = EventRegister::with('user', 'status')
                                     ->where('user_id', $userId)
                                     ->where('event_session_id', $request->session_id)
                                     ->first();
    
        if (!$registration) {
            return response()->json(['success' => false, 'message' => 'Pendaftaran tidak ditemukan. Pastikan QR Code benar.']);
        }
    
        // PERBAIKAN LOGIKA: Cek status pendaftaran saat ini sesuai Seeder Anda
        switch ($registration->status_id) {
            case 3: // ID 3 adalah "Pembayaran Diterima" -> Kondisi ideal untuk absensi
                $registration->update(['status_id' => 4]);
            
                // Catat di log kehadiran
                EventAttendanceLog::create([
                    'user_id' => $registration->user_id,
                    'event_session_id' => $registration->event_session_id,
                    'scan_time' => now(),
                    'qr_code' => $request->qr_code,
                ]);
            
                return response()->json([
                    'success' => true,
                    'message' => 'Kehadiran untuk ' . $registration->user->name . ' berhasil dicatat!'
                ]);
                break;
            
            case 4: // ID 4 adalah "Hadir"
                return response()->json(['success' => false, 'message' => 'Peserta (' . $registration->user->name . ') sudah pernah melakukan absensi.']);
                break;
            
            case 1: // ID 1 adalah "Menunggu Pembayaran"
            case 2: // ID 2 adalah "Menunggu Konfirmasi"
                return response()->json(['success' => false, 'message' => 'Pembayaran untuk peserta (' . $registration->user->name . ') belum dikonfirmasi.']);
                break;
            
            default: // Status lain seperti Dibatalkan, dll.
                return response()->json(['success' => false, 'message' => 'Peserta (' . $registration->user->name . ') tidak dapat melakukan absensi dengan status saat ini: ' . $registration->status->name]);
        }
    }

}
