<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'rating',
        'message',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
