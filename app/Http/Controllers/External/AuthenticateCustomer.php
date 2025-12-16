<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AuthCustomer;
use App\Http\Resources\External\AuthCustomerResource;
use App\Models\ApiClient;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCustomer extends Controller
{
    public function __invoke(AuthCustomer $authenticateClient)
    {
        $client = ApiClient::query()->where('email', $authenticateClient->email)->first();

        if (!$client || !Hash::check($authenticateClient->password, $client->password)) return response()->json([
            'message' => 'Credenciais inválidas. Verifique o email ou senha.'
        ], Response::HTTP_UNAUTHORIZED);

        $client->tokens()->delete();
        $token = $client->createToken('auth-token', ['*'])->plainTextToken;
        $client->load('events');

        return (new AuthCustomerResource($client, $token))
            ->response()
            ->setStatusCode(Response::HTTP_OK);

    }
}
