<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori event.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua kategori, urutkan berdasarkan nama, dan gunakan paginasi
        $categories = EventCategory::withCount('events')->latest()->paginate(10);
        
        // Arahkan ke view yang menampilkan tabel kategori
        return view('admin.event-categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori event baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Hanya menampilkan form kosong
        return view('admin.event-categories.create');
    }

    /**
     * Menyimpan kategori event baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input: nama wajib diisi, harus unik di tabel event_categories
        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name',
        ]);

        // Buat kategori baru
        EventCategory::create($request->all());

        // Arahkan kembali ke halaman daftar kategori dengan pesan sukses
        return redirect()->route('admin.event-categories.index')
                         ->with('success', 'Kategori event berhasil dibuat.');
    }

    /**
     * Menampilkan detail kategori (opsional, tidak selalu digunakan untuk CRUD sederhana).
     *
     * @param  \App\Models\EventCategory  $eventCategory
     * @return \Illuminate\View\View
     */
    public function show(EventCategory $eventCategory)
    {
        // Anda bisa membuat halaman detail jika diperlukan
        return view('admin.event-categories.show', compact('eventCategory'));
    }

    /**
     * Menampilkan form untuk mengedit kategori event.
     *
     * @param  \App\Models\EventCategory  $eventCategory
     * @return \Illuminate\View\View
     */
    public function edit(EventCategory $eventCategory)
    {
        // Mengirim data kategori yang ada ke form edit
        return view('admin.event-categories.edit', compact('eventCategory'));
    }

    /**
     * Memperbarui kategori event di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventCategory  $eventCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, EventCategory $eventCategory)
    {
        // Validasi input: nama wajib diisi, harus unik, kecuali untuk dirinya sendiri
        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name,' . $eventCategory->id,
        ]);

        // Update data kategori
        $eventCategory->update($request->all());

        
        return redirect()->route('admin.event-categories.index')
                         ->with('success', 'Kategori event berhasil diperbarui.');
    }

    /**

     *
     * @param  \App\Models\EventCategory  $eventCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(EventCategory $eventCategory)
    {
       
        if ($eventCategory->events()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa event.');
        }

  
        $eventCategory->delete();

  
        return redirect()->route('admin.event-categories.index')
                         ->with('success', 'Kategori event berhasil dihapus.');
    }
}
