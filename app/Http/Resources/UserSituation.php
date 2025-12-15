<?php

namespace App\Http\Resources;

use App\Enums\PaymentStatus;
use App\Utils\JsonHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSituation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $titleRaw = $this->payment->product->title ?? null;
        $categoryData = JsonHelper::decodeOrReturnRaw($titleRaw);

        if ($titleRaw) {
            $decodedTitles = json_decode($titleRaw, true);
            json_last_error() === JSON_ERROR_NONE && is_array($decodedTitles) ? $categoryData = $decodedTitles : $categoryData = $titleRaw;
        }

        return [
            'id' => $this->id_register,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'document' => $this->user->doc,
            'payment' => [
                'category' => $categoryData,
                'status' => match (true) {
                    $this->payment->pay === 8 => PaymentStatus::EXEMPT->label(),
                    $this->payment->status === PaymentStatus::RETURNED => PaymentStatus::PAID->label(),
                    default => $this->payment->status->label(),
                },
            ],
        ];
    }
}
