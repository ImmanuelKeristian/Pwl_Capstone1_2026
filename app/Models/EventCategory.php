<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory;

    protected $table = 'event_categories';
    protected $primaryKey = 'id';
    public $incrementing = false; // Based on migration: unsignedInteger('id')->primary()
    protected $keyType = 'int';

    protected $fillable = [
        'id', // If you manually assign IDs
        'name',
    ];

    /**
     * Get the events for the event category.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'event_category_id', 'id');
    }
}