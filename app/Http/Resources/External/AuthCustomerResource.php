<?php

namespace App\Http\Resources\External;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthCustomerResource extends JsonResource
{
    public string $plainTextToken;

    public function __construct($resource, string $token)
    {
        parent::__construct($resource);
        $this->plainTextToken = $token;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'access_token' => $this->plainTextToken,
            'token_type' => 'Bearer',
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'events_access' => [
                'total_events_linked' => $this->whenLoaded('events', fn () => $this->events->count()),
            ],
        ];
    }
}
