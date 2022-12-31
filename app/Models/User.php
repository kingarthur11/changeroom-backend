<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'country_id',
        'company_size'
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
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'phone_number' => 'string',
        'country_id' => 'integer',
    ];

    public static $rules = [
        'name' => 'required|string|max:100',
        'email' => 'required|string|max:100',
        'password' => 'required|min:8',
        'phone_number' => 'required|min:9|max:11',
        'country_id' => 'nullable|integer|exists:countries,id',
    ];

    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
