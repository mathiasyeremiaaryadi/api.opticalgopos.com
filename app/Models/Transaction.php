<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'code',
        'lens_type',
        'total', 
        'status',
        'payments_id',
        'categories_id',
        'customers_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relations
    public function payment() {
        return $this->belongsTo(Payment::class, 'payments_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customers_id', 'id');
    }

    // Mutators
    public function setLensTypeAttribute($value) {
        $this->attributes['lens_type'] = strtolower($value);
    }

    // Accessors
    public function getLensTypeAttribute($value) {
        return ucwords($value);
    }
}
