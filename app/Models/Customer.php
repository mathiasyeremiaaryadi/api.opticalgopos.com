<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relations
    public function prescription() {
        return $this->hasMany(Prescription::class, 'customers_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'customers_id');
    }

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
