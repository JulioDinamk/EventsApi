<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internal\EditCustomerRequest;
use App\Http\Resources\Internal\CustomerResource;
use App\Models\ApiClient;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EditCustomer extends Controller
{
    /**
     * Edita um cliente API.
     * @param EditCustomerRequest $customerRequest
     * @param int $customerId
     * @return JsonResponse
     */
    public function __invoke(EditCustomerRequest $customerRequest, int $customerId)
    {
        $customer = ApiClient::find($customerId);

        if (!$customer) return response()->json([
            'message' => 'Cliente não encontrado.',
        ], Response::HTTP_NOT_FOUND);

        $validated = $customerRequest->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if (!empty($validated)) {
            $customer->update($validated);
        }

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
