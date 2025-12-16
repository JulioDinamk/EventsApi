<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\CreateCustomer as CreateRequest ;
use App\Http\Resources\Internal\CustomerResource;
use App\Models\ApiClient;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CreateCustomer extends Controller
{
    /**
     * Cria um novo cliente API vinculando aos eventos fornecidos.
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function __invoke(CreateRequest $request): JsonResponse
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
        return (new CustomerResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
