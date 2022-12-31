<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $table = 'services';

    public $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];

    public static $rules = [
        'name' => 'required|string|max:100',
        'description' => 'nullable|string|max:100',
    ];

    
}
