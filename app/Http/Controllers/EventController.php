<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
// Hapus 'use App\Models\EventRegister;' jika tidak digunakan di tempat lain
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event untuk publik/guest.
     */
    public function guestIndex(Request $request)
    {
        $query = Event::with('eventCategory')
                      ->where('end_date', '>=', now()->toDateString()) 
                      ->orderBy('start_date', 'asc');
        
        $categories = EventCategory::orderBy('name')->get();
        $events = $query->paginate(9);
        return view('events.guest-index', compact('events', 'categories'));
    }

    /**
     * Menampilkan detail event untuk publik.
     */
    public function showPublic(Event $event)
    {
        // Eager load sessions untuk ditampilkan di halaman detail
        $event->load('sessions.eventRegistrations');
        return view('events.show-public', compact('event'));
    }

    /**
     * Menampilkan daftar event untuk panel panitia.
     */
    public function index(Request $request)
    {
        // Eager load jumlah sesi untuk ditampilkan di tabel
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
        // Eager load sesi beserta jumlah pendaftarnya.
        // Pengurutan sudah diatur di dalam relasi sessions() pada Model Event,
        // jadi kita tidak perlu menambahkan orderBy() lagi di sini.
        $event->load(['sessions' => function ($query) {
            $query->withCount('eventRegistrations');
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

    // METHOD REGISTER YANG USANG TELAH DIHAPUS DARI SINI
}
