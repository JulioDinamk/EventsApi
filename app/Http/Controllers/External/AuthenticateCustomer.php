<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AuthCustomer;
use App\Http\Resources\External\AuthCustomerResource;
use App\Models\ApiClient;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Dedoc\Scramble\Attributes\Response as ResponseDoc;

/**
 * Autenticação do Cliente
 * * Este endpoint valida as credenciais do cliente e retorna um token de acesso Sanctum.
 * * @unauthenticated
 */
class AuthenticateCustomer extends Controller
{
    #[Group('Customer')]
    #[Endpoint(
        title: 'Authenticate',
        description: 'Valida as credenciais (e-mail e senha) e gera um novo token de acesso para o cliente.
        Tokens anteriores são revogados no momento do login.',
    )]
    #[BodyParameter(
        name: 'email',
        description: 'Endereço de e-mail do usuário cadastrado no sistema',
        required: true,
        type: 'string'
    )]
    #[BodyParameter(
        name: 'password',
        description: 'Senha do usuário cadastrado no sistema.',
        required: true,
        type: 'string'
    )]

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
