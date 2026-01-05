<?php

namespace App\Http\Resources\External;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerEventsResource extends JsonResource
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
            'events_access' => [
                'total_events_linked' => $this->whenLoaded('events', fn () => $this->events->count()),
                'events' => $this->whenLoaded('events', fn () => $this->events->pluck('id_uuid', 'title')),
            ],
        ];
    }
}
