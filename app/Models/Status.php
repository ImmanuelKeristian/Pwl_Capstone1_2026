<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    protected $primaryKey = 'id';
    public $incrementing = false; // Based on migration: unsignedTinyInteger('id')->primary()
    protected $keyType = 'int'; // Or 'int' if using unsignedInteger

    protected $fillable = [
        'id', // If you manually assign IDs
        'name',
    ];

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegister::class, 'status_id', 'id');
    }
}