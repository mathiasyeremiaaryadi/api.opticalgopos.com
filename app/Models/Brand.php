<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'products_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relations
    public function product() {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

    public function stocks() {
        return $this->hasMany(Stock::class, 'brands_id');
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
