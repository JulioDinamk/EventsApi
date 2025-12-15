<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ManagerRegistersEvent extends Model
{
     protected $primaryKey = 'id_register';

    public function user(): BelongsTo
    {
        return $this->belongsTo(ManagerRegister::class, 'id_register');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(ManagerPayment::class, 'id_register')
            ->where('id_type', 1);
    }
}
