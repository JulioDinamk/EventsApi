<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\EventRequest;
use App\Http\Resources\Internal\CustomerResource;
use App\Models\ApiClient;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AddEvent extends Controller
{
    /**
     * Anexa novos eventos a um cliente API existente.
     * @param EventRequest $addEventRequest
     * @param int $customerId
     * @return JsonResponse
     */
    public function __invoke(EventRequest $addEventRequest, int $customerId)
    {
        $client = ApiClient::find($customerId);

        if (!$client) return response()->json(['message' => 'Cliente não encontrado.',], Response::HTTP_NOT_FOUND);

        $eventUuids = $addEventRequest->validated('event_uuids');
        $eventsToAdd = Event::query()
            ->whereIn('id_uuid', $eventUuids)
            ->pluck('id_event')
            ->toArray();

        if (empty($eventsToAdd)) throw ValidationException::withMessages(['event_uuids' => ['Nenhum evento válido encontrado para os UUIDs fornecidos.']]);

        $currentEventIds = $client->events()->pluck('event_id')->toArray();
        $newEventsToAttach = array_diff($eventsToAdd, $currentEventIds);

        if (!empty($newEventsToAttach)) {
            $client->events()->attach($newEventsToAttach);
        } else {
            $client->load('events');
            return response()->json([
                'message' => 'Todos os eventos fornecidos já estavam vinculados ao cliente.',
                'customer' => (new CustomerResource($client))
            ], Response::HTTP_OK);
        }

        $client->load('events');

        return (new CustomerResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
