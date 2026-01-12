<?php

namespace App\Http\Controllers\External\Members;

use App\Http\Controllers\Controller;
use App\Http\Resources\External\MemberInfoResource;
use App\Models\Event;
use App\Models\ManagerRegistersEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberInformation extends Controller
{

    /**
     * Busca as informações de um usuário (register) do evento fornecido.
     * @param Request $request
     * @param string $eventUuid
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $eventUuid)
    {
        $authenticatedClient = $request->user();
        $event = Event::query()->where('id_uuid', $eventUuid)->first();

        if (!$event) return response()->json(['message' => 'Evento não reconhecido.'], Response::HTTP_NOT_FOUND);

        $authenticatedClient->load('events');
        $hasPermission = $authenticatedClient->events()->where('event_id', $event->id_event)->exists();

        if (!$hasPermission) return response()->json([
            'message' => "Acesso negado. O seu token não possui permissão para o evento: {$eventUuid}."
        ], Response::HTTP_FORBIDDEN);

        $email = $request->query('email');
        $cpf = $request->query('cpf');

        if (!$email && !$cpf) return response()->json([
            'message' => 'Necessário informar E-mail ou CPF.'
        ], Response::HTTP_BAD_REQUEST);

        $userEventRelation = ManagerRegistersEvent::query()
            ->where('id_event', $event->id_event)
            ->whereRelation('user', fn($query) => $query->where(fn($q) => $q->when($email, fn($q) => $q->where('email', $email))
                ->when($cpf, fn($q) => $q->where('doc', $cpf))
            ))
            ->with([
                'user:id_register,name,email,doc',
                'payment' => fn($query) => $query
                    ->select(['id_payment', 'id_register', 'id_product', 'pay', 'status'])
                    ->where('id_event', $event->id_event)
                    ->with('product:id_product,title')
            ])
            ->select(['id_register', 'id_event', 'accredited', 'accredited_time'])
            ->first();

        if (!$userEventRelation) return response()->json([
            'message' => "O usuário não foi encontrado para o email ou CPF fornecidos."
        ], Response::HTTP_BAD_REQUEST);

        return (new MemberInfoResource($userEventRelation))->response()->setStatusCode(Response::HTTP_OK);

    }
}
