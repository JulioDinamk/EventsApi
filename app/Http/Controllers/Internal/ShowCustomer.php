<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Resources\Internal\CustomerResource;
use App\Models\ApiClient;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ShowCustomer extends Controller
{
    /**
     * Busca um cliente API pelo seu ID primário e retorna seus detalhes.
     * @param int $id O ID primário do cliente.
     * @return CustomerResource|JsonResponse
     */
    public function __invoke(int $id): CustomerResource|JsonResponse
    {
        $client = ApiClient::query()->with('events')->find($id);

        if (!$client) return response()->json(['message' => 'Cliente não encontrado.'], Response::HTTP_NOT_FOUND);
        return (new CustomerResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
