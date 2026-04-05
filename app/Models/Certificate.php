<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    // Model sekarang menggunakan primary key 'id' standar Laravel
    // jadi tidak perlu lagi properti $primaryKey, $incrementing, dll.

    /**
     * Kolom yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'event_register_id',
        'file_path',
        'file_name',
    ];

    /**
     * Relasi ke pendaftaran event yang memiliki sertifikat ini.
     */
    public function eventRegister()
    {
        return $this->belongsTo(EventRegister::class, 'event_register_id');
    }
}
