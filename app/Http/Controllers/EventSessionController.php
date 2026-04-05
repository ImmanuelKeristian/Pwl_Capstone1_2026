<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventRegister;
use Illuminate\Http\Request;

class EventSessionController extends Controller
{
    /** Menampilkan form untuk membuat sesi baru untuk event tertentu. */
    public function create(Event $event)
    {
        return view('committee.sessions.create', compact('event'));
    }

    /** Menyimpan sesi baru ke database. */
    public function store(Request $request, Event $event) {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_date' => ['required', 'date', 'after_or_equal:'.$event->start_date->format('Y-m-d'), 'before_or_equal:'.$event->end_date->format('Y-m-d')],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'registration_fee' => 'required|numeric|min:0',
        ]);
        $event->sessions()->create($validatedData);
        return redirect()->route('committee.events.show', $event->id)->with('success', 'Sesi baru berhasil ditambahkan.');
    }

    /** Menampilkan form untuk mengedit sesi. */
    public function edit(EventSession $session)
    {
        return view('committee.sessions.edit', compact('session'));
    }

    /** Memperbarui sesi di database. */
    public function update(Request $request, EventSession $session) {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_date' => ['required', 'date', 'after_or_equal:'.$session->event->start_date->format('Y-m-d'), 'before_or_equal:'.$session->event->end_date->format('Y-m-d')],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'registration_fee' => 'required|numeric|min:0',
        ]);
        $session->update($validatedData);
        return redirect()->route('committee.events.show', $session->event_id)->with('success', 'Sesi berhasil diperbarui.');
    }

    /** Menghapus sesi dari database. */
    public function destroy(EventSession $session)
    {
        $eventId = $session->event_id;
        $session->delete();

        return redirect()->route('committee.events.show', $eventId)
                         ->with('success', 'Sesi berhasil dihapus.');
    }
    
    /** Mendaftarkan member ke sebuah sesi. */
    public function register(Request $request, EventSession $session)
    {
        $user = $request->user();
    
        // Cek kuota dan apakah sudah terdaftar
        if ($session->eventRegistrations()->count() >= $session->max_participants) {
            return back()->with('error', 'Maaf, kuota untuk sesi ini sudah penuh.');
        }
        if ($user->eventRegistrations()->where('event_session_id', $session->id)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar di sesi ini.');
        }
    
        // PERBAIKAN DI SINI: Pastikan event_session_id disertakan
        EventRegister::create([
            'user_id' => $user->id,
            'event_session_id' => $session->id, // Ini yang paling penting
            'status_id' => 1,                 // Asumsi 1 = Menunggu Pembayaran
        ]);
    
        // ... sisa logika redirect ...
        if ($session->registration_fee > 0) {
            return redirect()->route('home')->with('success', 'Anda berhasil mendaftar! Silakan lanjutkan pembayaran.');
        } 
        return redirect()->route('home')->with('success', 'Anda berhasil terdaftar di event gratis ini!');
    }
}
