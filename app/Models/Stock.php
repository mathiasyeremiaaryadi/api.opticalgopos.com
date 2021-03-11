<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'code',
        'type',
        'color', 
        'quantity',
        'brands_id',
        'categories_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relations
    public function brand() {
        return $this->belongsTo(Brand::class, 'brands_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }

    // Mutators
    public function setTypeAttribute($value) {
        $this->attributes['type'] = strtolower($value);
    }

    public function setColorAttribute($value) {
        $this->attributes['color'] = strtolower($value);
    }

    // Accessors
    public function getTypeAttribute($value) {
        return ucwords($value);
    }

    public function getColorAttribute($value) {
        return ucwords($value);
    }
}
