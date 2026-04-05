<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventSession;
use App\Models\EventRegister;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(EventSession $session)
    {
        $attendees = EventRegister::with(['user', 'certificate'])
                                  ->where('event_session_id', $session->id)
                                  ->where('status_id', 4) // Asumsi ID 4 adalah "Hadir"
                                  ->paginate(20);
        return view('committee.certificates.index', compact('session', 'attendees'));
    }

    public function upload(Request $request, EventRegister $registration)
    {
        $request->validate(['certificate_file' => 'required|file|mimes:pdf|max:2048']);

        if ($registration->certificate) {
            Storage::disk('public')->delete($registration->certificate->file_path);
            $registration->certificate->delete();
        }

        $path = $request->file('certificate_file')->store('certificates', 'public');

        // PERBAIKAN: Membuat sertifikat dengan relasi yang lebih sederhana
        $registration->certificate()->create([
            'file_path' => $path,
            'file_name' => $request->file('certificate_file')->getClientOriginalName(),
        ]);

        return back()->with('success', 'Sertifikat untuk ' . $registration->user->name . ' berhasil diupload.');
    }
}
