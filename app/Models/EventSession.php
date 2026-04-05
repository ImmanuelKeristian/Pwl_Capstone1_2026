<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Pastikan Carbon di-import

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'title', 'description', 'session_date', 
        'start_time', 'end_time', 'location', 'speaker', 
        'max_participants', 'registration_fee'
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    /**
     * PERBAIKAN DI SINI:
     * Accessor untuk membuat atribut 'start_datetime' secara dinamis.
     * Ini menggabungkan 'session_date' dan 'start_time'.
     * Sekarang Anda bisa memanggilnya di view: $session->start_datetime
     */
    public function getStartDatetimeAttribute(): Carbon
    {
        return Carbon::parse($this->session_date->format('Y-m-d') . ' ' . $this->start_time);
    }

    // Relasi ke event induk
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke pendaftar
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegister::class);
    }
}