<?php

namespace App\Http\Resources\Internal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'last_updated' => $this->updated_at->format('Y-m-d H:i:s'),
            'events_access' => [
                'total_events_linked' => $this->whenLoaded('events', fn () => $this->events->count()),
                'event_uuids' => $this->whenLoaded('events', fn () => $this->events->pluck('id_uuid', 'id_event')),
            ],
        ];
    }
}
