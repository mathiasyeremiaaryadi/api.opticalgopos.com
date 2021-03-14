<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'code', 
        'name', 
        'description', 
        'price'
    ];

    protected $hidden = [
        'created_at', 
        'updated_at'
    ];

    // Relations
    public function stocks() {
        return $this->hasMany(Stock::class, 'categories_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'categories_id');
    }

    // Mutators
    public function setNameAttribute($value) {
        $this->attributes['name'] = strtolower($value);
    }

    public function setDescriptionAttribute($value) {
        $this->attributes['description'] = strtolower($value);
    }

    // Accessors
    public function getNameAttribute($value) {
        return ucwords($value);
    }

    public function getCategoryDescriptionAttribute($value) {
        return ucfirst($value);
    }
}
