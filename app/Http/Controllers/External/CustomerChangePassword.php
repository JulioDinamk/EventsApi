<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ChangePassword;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerChangePassword extends Controller
{
    #[Group('Customer')]
    #[Endpoint(
        title: 'Change Password',
        description: 'Permite que um usuário autenticado altere sua senha atual. A requisição atualiza a senha do usuário no sistema de forma segura.',
    )]

    #[BodyParameter(
        name: 'password',
        description: 'Nova senha que substituirá a atual',
    )]
    public function __invoke(ChangePassword $changePassword)
    {
        $customer = $changePassword->user();
        $customer->update(['password' => $changePassword->validated('password')]);

        return response()->json([
            'message' => 'Senha atualizada com sucesso.'
        ], Response::HTTP_OK);
    }
}
