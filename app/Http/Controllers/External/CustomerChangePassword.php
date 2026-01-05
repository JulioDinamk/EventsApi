<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ChangePassword;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerChangePassword extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ChangePassword $changePassword)
    {
        $customer = $changePassword->user();
        $customer->update(['password' => $changePassword->validated('password')]);

        return response()->json([
            'message' => 'Senha atualizada com sucesso.'
        ], Response::HTTP_OK);
    }
}
