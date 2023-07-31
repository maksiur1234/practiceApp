<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'date',
        'company_id',
        'user_id',
        'type_id',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }


    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }
}
