<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProgress extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'file',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
