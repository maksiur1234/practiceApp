<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'event_start',
        'event_end',
        'company_id',
<<<<<<< HEAD
        'user_id',
        'visit_date',
=======
>>>>>>> origin/main
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
