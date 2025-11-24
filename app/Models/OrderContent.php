<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderContent extends Model
{
    protected $fillable = [
        'order_id',
        'content_type',
        'file_path',
        'caption'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
