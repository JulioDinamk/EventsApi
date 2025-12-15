<?php

namespace App\Http\Controllers\ApiManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiClientResource;
use App\Models\ApiClient;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ShowApiClient extends Controller
{
    /**
     * Busca um cliente API pelo seu ID primário e retorna seus detalhes.
     * @param int $id O ID primário do cliente.
     * @return ApiClientResource|JsonResponse
     */
    public function __invoke(int $id): ApiClientResource|JsonResponse
    {
        $client = ApiClient::query()->with('events')->find($id);

        if (!$client) return response()->json(['message' => 'Cliente não encontrado.'], Response::HTTP_NOT_FOUND);
        return (new ApiClientResource($client))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
