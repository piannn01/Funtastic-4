<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Order extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'email',
        'phone',
        'instagram',
        'instagram_username',
        'notes',
        'price',
        'payment_status',
        'status',
        'midtrans_token',
        'midtrans_order_id',
        'invoice_code',
        'redirect_url',
        'kode_unik',
        'progress',
        'progress_note',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function contents()
    {
        return $this->hasMany(OrderContent::class);
    }

    public function progressItems()
    {
        return $this->hasMany(OrderProgress::class, 'order_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG ULANG PROGRESS
    |--------------------------------------------------------------------------
    */
    public function refreshProgress()
    {
        $total = $this->progressItems()->count();
        $done  = $this->progressItems()->where('status', 'Selesai')->count();

        $progress = $total == 0 ? 0 : round(($done / $total) * 100);

        $this->progress = $progress;
        $this->progress_note = "Progress otomatis: {$progress}%";
        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE JADWAL PROGRESS OTOMATIS
    | (2 HARI SEKALI — FEED, STORY, REELS DIKIRIM BERSAMAAN)
    |--------------------------------------------------------------------------
    */
    public function generateDefaultProgress()
    {
        $service = $this->service;
        if (!$service) return;

        // Ambil jumlah konten dari layanan
        $feeds  = (int) ($service->feed ?? 0);
        $stories = (int) ($service->stories ?? 0);
        $reels  = (int) ($service->video_reels ?? 0);

        // Ambil nilai terbesar
        $maxRows = max($feeds, $stories, $reels);
        if ($maxRows == 0) return;

        // Mulai dari tanggal order dibuat
        $start = Carbon::parse($this->created_at);
        $rows = [];

        for ($i = 1; $i <= $maxRows; $i++) {

            // 2 hari sekali
            $date = $start->copy()->addDays(($i - 1) * 2);

            // FEED
            if ($i <= $feeds) {
                $rows[] = [
                    'order_id'       => $this->id,
                    'content_type'   => 'feed',
                    'content_index'  => $i,
                    'scheduled_date' => $date,
                    'status'         => 'Belum',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }

            // STORY
            if ($i <= $stories) {
                $rows[] = [
                    'order_id'       => $this->id,
                    'content_type'   => 'story',
                    'content_index'  => $i,
                    'scheduled_date' => $date,
                    'status'         => 'Belum',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }

            // REELS
            if ($i <= $reels) {
                $rows[] = [
                    'order_id'       => $this->id,
                    'content_type'   => 'reels',
                    'content_index'  => $i,
                    'scheduled_date' => $date,
                    'status'         => 'Belum',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        // Insert sekaligus (lebih cepat)
        DB::table('order_progress')->insert($rows);
    }
}
