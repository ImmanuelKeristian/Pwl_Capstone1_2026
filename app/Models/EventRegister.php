<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegister extends Model
{
    use HasFactory;

    protected $table = 'event_register';

    // Karena kita sekarang menggunakan primary key 'id' standar, kita tidak perlu lagi
    // kode-kode rumit untuk menangani composite key.
    // Properti seperti $primaryKey, $incrementing, dan method setKeysForSaveQuery()
    // bisa dihapus.

    /**
     * Kolom yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'event_session_id',
        'status_id',
        'payment_file',
    ];

    /**
     * Relasi ke pengguna yang mendaftar.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relasi ke sesi event yang didaftarkan.
     */
    public function eventSession()
    {
        return $this->belongsTo(EventSession::class);
    }

    /**
     * Relasi ke status pendaftaran.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function certificate()
    {
        // PERBAIKAN: Relasi hasOne yang sederhana
        return $this->hasOne(Certificate::class, 'event_register_id');
    }
}
