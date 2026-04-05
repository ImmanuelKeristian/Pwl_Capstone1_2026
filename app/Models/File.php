<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // For URL generation

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $primaryKey = 'id';
    public $incrementing = false; // Based on migration: unsignedInteger('id')->primary()
    protected $keyType = 'int';

    protected $fillable = [
        'id', // If you manually assign IDs
        'file_name',
        'file_path',
        'file_type', // Should match migration (MEDIUMBLOB -> string for type, path for storage)
                     // If storing blob in DB, then 'file_content' or similar and cast to binary.
                     // Assuming file_type is MIME type and file_path stores the location.
        'event_register_user_id',
        'event_register_event_id',
    ];

    // If file_path stores path in 'public' disk:
    // public function getUrlAttribute()
    // {
    //     if ($this->file_path) {
    //         return Storage::disk('public')->url($this->file_path);
    //     }
    //     return null;
    // }

    /**
     * Get the event registration that this file belongs to (if any).
     */
    public function eventRegister()
    {
        if ($this->event_register_user_id && $this->event_register_event_id) {
            return EventRegister::where('user_id', $this->event_register_user_id)
                                ->where('event_id', $this->event_register_event_id)
                                ->first();
        }
        return null;
    }

    // For easier access to user and event directly if associated:
    public function user() {
        return $this->belongsTo(User::class, 'event_register_user_id', 'id');
    }

    public function event() {
        return $this->belongsTo(Event::class, 'event_register_event_id', 'id');
    }
}