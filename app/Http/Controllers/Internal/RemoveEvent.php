<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\EventRequest;
use App\Models\ApiClient;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RemoveEvent extends Controller
{
    /**
     * Remove o vínculo de eventos de um cliente API existente.
     * @param EventRequest $eventRequest
     * @param int $customerId
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function __invoke(EventRequest $eventRequest, int $customerId)
    {
        $client = ApiClient::find($customerId);

        if (!$client) return response()->json([
            'message' => 'Cliente não encontrado.',
        ], Response::HTTP_NOT_FOUND);

        $eventUuids = $eventRequest->validated('event_uuids');
        $eventsToRemove = Event::query()
            ->whereIn('id_uuid', $eventUuids)
            ->pluck('id_event')
            ->toArray();

        if (empty($eventsToRemove)) throw ValidationException::withMessages([
            'event_uuids' => ['Nenhum evento válido encontrado para os UUIDs fornecidos.'],
        ]);

        $countDetached = $client->events()->detach($eventsToRemove);

        return response()->noContent();
    }
}
