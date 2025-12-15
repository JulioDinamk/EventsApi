<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $table = 'manager_events';
    protected $primaryKey = 'id_event';

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(
            ApiClient::class,
            'api_client_event',
            'event_id',
            'api_client_id'
        );
    }
}
