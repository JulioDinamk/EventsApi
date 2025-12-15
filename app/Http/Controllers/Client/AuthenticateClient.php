<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Http\Requests\Client\AuthenticateClient as AuthenticateClientRequest;
use App\Http\Resources\AuthenticateClient as AuthenticateClientResource;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateClient extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AuthenticateClientRequest $authenticateClient)
    {
        $client = ApiClient::query()->where('email', $authenticateClient->email)->first();

        if (!$client || !Hash::check($authenticateClient->password, $client->password)) return response()->json([
            'message' => 'Credenciais inválidas. Verifique o email ou senha.'
        ], Response::HTTP_UNAUTHORIZED);

        $client->tokens()->delete();
        $token = $client->createToken('auth-token', ['*'])->plainTextToken;
        $client->load('events');

        return (new AuthenticateClientResource($client, $token))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }
}
