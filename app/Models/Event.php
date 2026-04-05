<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'event_category_id', 
        'poster_kegiatan', 
        'start_date', 
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relasi ke kategori event.
     */
    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class);
    }

    /**
     * PERBAIKAN DI SINI:
     * Relasi ke sesi-sesi event, diurutkan berdasarkan kolom asli di database.
     */
    public function sessions()
    {
        // Mengurutkan berdasarkan tanggal sesi, lalu berdasarkan waktu mulai sesi.
        return $this->hasMany(EventSession::class)
                    ->orderBy('session_date', 'asc')
                    ->orderBy('start_time', 'asc');
    }

    /**
     * Relasi untuk mendapatkan semua pendaftar melalui sesi.
     */
    public function eventRegistrations()
    {
        return $this->hasManyThrough(EventRegister::class, EventSession::class);
    }
}
