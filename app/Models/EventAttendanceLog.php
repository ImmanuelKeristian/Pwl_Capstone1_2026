<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendanceLog extends Model
{
    use HasFactory;

    protected $table = 'event_attendance_logs';
    protected $primaryKey = 'id';
    public $incrementing = false; // Based on migration: unsignedInteger('id')->primary()
    protected $keyType = 'int';

    protected $fillable = [
        'id', // If you manually assign IDs
        'event_register_user_id',
        'event_register_event_id',
        'scan_time', // Usually set by DB or app logic
        'qr_code',
    ];

    protected $casts = [
        'scan_time' => 'datetime',
    ];

    /**
     * Get the event registration associated with this attendance log.
     */
    public function eventRegister()
    {
        return EventRegister::where('user_id', $this->event_register_user_id)
                            ->where('event_id', $this->event_register_event_id)
                            ->first();
    }

    // For easier access to user and event directly:
    public function user() {
        return $this->belongsTo(User::class, 'event_register_user_id', 'id');
    }

    public function event() {
        return $this->belongsTo(Event::class, 'event_register_event_id', 'id');
    }
}