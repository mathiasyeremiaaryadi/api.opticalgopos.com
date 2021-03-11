<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'right_spherical',
        'right_cylinder',
        'right_plus',
        'right_axis',
        'right_pupil_distance',
        'left_spherical',
        'left_cylinder',
        'left_plus',
        'left_axis',
        'left_pupil_distance',
        'customers_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relations
    public function customer() {
        return $this->belongsTo(Customer::class, 'customers_id', 'id');
    }
}
