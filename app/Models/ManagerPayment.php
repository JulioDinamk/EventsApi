<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagerPayment extends Model
{
    protected $primaryKey = 'id_payment';
    public $timestamps = false;

    protected $casts = [
        'status' => PaymentStatus::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ManagerProduct::class, 'id_product');
    }

}
