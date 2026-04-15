<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event untuk publik/guest.
     */
    public function guestIndex(Request $request)
    {
        // 1. Query untuk Daftar Event
        $query = Event::with('eventCategory')
                      ->where('end_date', '>=', now()->toDateString()) 
                      ->orderBy('start_date', 'asc');
        
        if ($request->filled('category')) {
            $query->where('event_category_id', $request->category);
        }
        
        $categories = EventCategory::orderBy('name')->get();
        $events = $query->paginate(9);

        // 2. Inisialisasi Variabel Analitik Default
        $totalRevenue = 0;
        $totalSales = 0;
        $activeEventsCount = 0;
        $attendanceRate = 0; 
        $chartLabels = [];
        $chartData = [];

        // 3. Hitung Analitik Jika User Login & Bukan Member
        if (Auth::check() && Auth::user()->role !== 'member') {
            
            // Total Registrasi Keseluruhan (dari EventRegister)
            $totalSales = EventRegister::count(); 

            // Total Pendapatan
            // Karena EventRegister terhubung ke EventSession, kita join 2 kali untuk mencapai tabel events
            $totalRevenue = EventRegister::join('event_sessions', 'event_register.event_session_id', '=', 'event_sessions.id')
                    ->sum('event_sessions.registration_fee');

            // Jumlah Event Aktif
            $activeEventsCount = Event::where('end_date', '>=', now())->count();

            // Data Grafik Registrasi per Bulan (Tahun Ini) menggunakan EventRegister
            $registrationsThisYear = EventRegister::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month');

            $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = $namaBulan[$i - 1];
                $chartData[] = $registrationsThisYear->has($i) ? $registrationsThisYear[$i] : 0; 
            }
        }

        return view('events.guest-index', compact(
            'events', 
            'categories',
            'totalRevenue',
            'totalSales',
            'activeEventsCount',
            'attendanceRate',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Menampilkan detail event untuk publik.
     */
    public function showPublic(Event $event)
    {
        $event->load('sessions.eventRegisters'); 
        return view('events.show-public', compact('event'));
    }

    /**
     * Menampilkan daftar event untuk panel panitia.
     */
    public function index(Request $request)
    {
        $query = Event::with('eventCategory')->withCount('sessions')->latest('start_date');
        $events = $query->paginate(15);
        return view('committee.events.index', compact('events'));
    }

    /**
     * Menampilkan form untuk membuat event induk baru.
     */
    public function create()
    {
        $categories = EventCategory::orderBy('name')->get();
        return view('committee.events.create', compact('categories'));
    }

    /**
     * Menyimpan event induk baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_category_id' => 'required|integer|exists:event_categories,id',
            'poster_kegiatan' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('poster_kegiatan')) {
            $path = $request->file('poster_kegiatan')->store('event_posters', 'public');
            $validatedData['poster_kegiatan'] = $path;
        }

        $event = Event::create($validatedData);

        return redirect()->route('committee.events.show', $event->id)
                         ->with('success', 'Event induk berhasil dibuat. Sekarang, silakan tambahkan sesi.');
    }

    /**
     * Menampilkan detail event dan daftar sesinya di panel panitia.
     */
    public function show(Event $event)
    {
        $event->load(['sessions' => function ($query) {
            $query->withCount('eventRegisters'); 
        }]);
        
        return view('committee.events.show', compact('event'));
    }

    /**
     * Menampilkan form untuk mengedit event induk.
     */
    public function edit(Event $event)
    {
        $categories = EventCategory::orderBy('name')->get();
        return view('committee.events.edit', compact('event', 'categories'));
    }

    /**
     * Memperbarui event induk di database.
     */
    public function update(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_category_id' => 'required|integer|exists:event_categories,id',
            'poster_kegiatan' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('poster_kegiatan')) {
            if ($event->poster_kegiatan) {
                Storage::disk('public')->delete($event->poster_kegiatan);
            }
            $path = $request->file('poster_kegiatan')->store('event_posters', 'public');
            $validatedData['poster_kegiatan'] = $path;
        }

        $event->update($validatedData);

        return redirect()->route('committee.events.show', $event->id)
                         ->with('success', 'Event induk berhasil diperbarui.');
    }

    /**
     * Menghapus event induk dari database.
     */
    public function destroy(Event $event)
    {
        if ($event->poster_kegiatan) {
            Storage::disk('public')->delete($event->poster_kegiatan);
        }
        
        $event->delete();
        return redirect()->route('committee.events.index')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Export data event ke format CSV (Excel)
     */
    public function exportExcel()
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }

        $events = Event::with('eventCategory')->get();
        $fileName = 'laporan_event_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Judul Event', 'Kategori', 'Tanggal Mulai', 'Tanggal Selesai', 'Lokasi', 'Biaya (Rp)'];

        $callback = function() use($events, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->title,
                    $event->eventCategory ? $event->eventCategory->name : 'Tidak ada',
                    $event->start_date,
                    $event->end_date,
                    $event->lokasi ?? 'Tidak disetel',
                    $event->registration_fee ?? 0
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data event ke format PDF
     */
    public function exportPdf()
    {
        if (!Auth::check() || Auth::user()->role === 'member') {
            abort(403, 'Akses ditolak.');
        }

        $events = Event::with('eventCategory')->get(); 

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('events.exports.pdf', compact('events'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan_event_' . date('Y-m-d') . '.pdf'); 
    }
}