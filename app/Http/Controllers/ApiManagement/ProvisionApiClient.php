<?php

namespace App\Http\Controllers\ApiManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\CreateClient;
use App\Http\Resources\ApiClientResource;
use App\Models\ApiClient;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProvisionApiClient extends Controller
{
    /**
     * Cria um novo cliente API vinculando aos eventos fornecidos.
     * @param CreateClient $request
     * @return JsonResponse
     */
    public function __invoke(CreateClient $request): JsonResponse
    {
        $events = Event::query()->whereIn('id_uuid', $request->input('event_uuids'))->pluck('id_event');

        if ($events->isEmpty()) throw ValidationException::withMessages([
            'event_uuids' => ['Nenhum evento válido encontrado para os UUIDs fornecidos.'],
        ]);

        $rawPassword = $request->input('password') ?? Str::random(12);

        $client = ApiClient::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($rawPassword),
        ]);

        $client->events()->attach($events);
//        $token = $client->createToken('event-access-token')->plainTextToken;
        $client->load('events');
        return (new ApiClientResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
