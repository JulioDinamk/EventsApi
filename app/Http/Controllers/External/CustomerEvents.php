<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Resources\External\CustomerEventsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerEvents extends Controller
{
    /**
     * Retorna os eventos vinculados a um cliente API.
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $customer = $request->user();
        $customer->load('events');

        return (new CustomerEventsResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

}
