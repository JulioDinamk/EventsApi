<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Resources\External\CustomerEventsResource;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerEvents extends Controller
{
    #[Group('Customer')]
    #[Endpoint(
        title: 'Costumer Available Events',
        description: 'Lista completa de eventos vinculados a seu usuário. Use esta requisição para obter o nome e uuid sobre todos os eventos disponíveis.',
    )]
    public function __invoke(Request $request)
    {
        $customer = $request->user();
        $customer->load('events');

        return (new CustomerEventsResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

}
