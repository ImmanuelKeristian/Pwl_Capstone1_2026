<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     * Our migration for users was:
     * $table->unsignedInteger('id');
     * $table->primary('id');
     * This is NOT auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', // If you manually assign IDs
        'name',
        'email',
        'password',
        'role',
        'email_verified_at', // Allow mass assignment if needed during specific workflows
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the event registrations for the user.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegister::class, 'user_id', 'id');
    }

    // You could also define a relationship to events through event_registrations:
    // public function events()
    // {
    //     return $this->hasManyThrough(Event::class, EventRegister::class, 'user_id', 'id', 'id', 'event_id');
    // }
}