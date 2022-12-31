<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $table = 'countries';

    public $fillable = [
        'name'
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public static $rules = [
        'name' => 'required|string|max:100',
    ];

    
}
