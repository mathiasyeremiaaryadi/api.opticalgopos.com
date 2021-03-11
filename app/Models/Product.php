<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relations
    public function brands() {
        return $this->hasMany(Brand::class, 'products_id');
    }

    // Mutators
    public function setNameAttribute($value) {
        $this->attributes['name'] = strtolower($value);
    }

    // Accessors
    public function getNameAttribute($value) {
        return ucwords($value);
    }
}
