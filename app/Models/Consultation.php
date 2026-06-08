<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'specialty',
        'message', 'preferred_date', 'time_slot',
        'status', 'notes',
    ];
}
