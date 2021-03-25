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
        'transaction_date',
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

    public function setStatusAttribute($value) {
        if($value == 'Gagal') {
            $this->attributes['status'] = 0;
        } else if($value == 'Sukses') {
            $this->attributes['status'] = 1;
        } else {
            $this->attributes['status'] = 2;
        }
    }

    // Accessors
    public function getLensTypeAttribute($value) {
        return ucwords($value);
    }

    public function getStatusAttribute($value) {
        if($value == 0) {
            return ucwords('gagal');
        } else if($value == 1) {
            return ucwords('sukses');
        } else {
            return ucwords('pending');
        }
    }
}
