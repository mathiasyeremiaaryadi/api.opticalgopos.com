<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'date_of_birth',
        'email',
        'password',
        'image'
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];

    // Mutators
    public function setNameAttribute($value) {
        $this->attributes['name'] = strtolower($value);
    }

    public function setAddressAttribute($value) {
        $this->attributes['address'] = strtolower($value);
    }

    // Accessors
    public function getNameAttribute($value) {
        return ucwords($value);
    }

    public function getAddressAttribute($value) {
        return ucwords($value);
    }
}
