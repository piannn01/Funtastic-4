<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        // pastikan field jumlah konten & durasi ada di sini juga
        'feeds',
        'stories',
        'reels',
        'duration_days',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Total slot konten (feed + story + reels)
     */
    public function totalContentSlots(): int
    {
        return (int) $this->feeds + (int) $this->stories + (int) $this->reels;
    }

    /**
     * Durasi paket (default 30 hari kalau null)
     */
    public function durationInDays(): int
    {
        return (int) ($this->duration_days ?: 30);
    }
}
