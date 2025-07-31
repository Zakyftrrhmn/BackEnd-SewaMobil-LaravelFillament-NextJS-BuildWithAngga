<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'proof',
        'booking_trx_id',
        'started_at',
        'total_amount',
        'duration',
        'price',
        'insurance',
        'total_max_amount',
        'is_paid',
        'vehicle_id',
        'alpina_store_id',
    ];

    public static function generateUniqueTrxId()
    {
        $prefix = 'AlpaBWA';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function alpinaStore(): BelongsTo
    {
        return $this->belongsTo(AlpinaStore::class, 'alpina_store_id');
    }
}
