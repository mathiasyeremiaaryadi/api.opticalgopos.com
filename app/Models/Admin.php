<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
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

    // Accessors
    public function getNameAttribute($value) {
        return ucwords($value);
    }
}
