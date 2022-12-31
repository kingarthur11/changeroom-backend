<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service;

class Company extends Model
{
    public $table = 'companies';

    public $fillable = [
        'company_name',
        'company_email',
        'service_id',
        'country_id',
        'user_id'
    ];

    protected $casts = [
        'company_name' => 'string',
        'company_email' => 'string',
        'service_id' => 'integer',
        'country_id' => 'integer',
    ];

    public static $rules = [
        'company_name' => 'required|string|max:100',
        'company_email' => 'required|string|max:100',
        'service_id' => 'nullable|integer',
        'country_id' => 'nullable|integer',
    ];

    public function service(){
        return $this->belongsTo(Service::class, 'service_id', 'id')->orderBy('created_at', 'DESC');
    }

    // public function scopeCompany($query, $email)
    // {
    //     return $query->where('email', $email);
    // }
}
