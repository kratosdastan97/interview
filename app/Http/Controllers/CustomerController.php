<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $customers = Customer::active()->get('name', 'last_name', 'address', 'description', 'id_reg','id_com');
        return $this->successData($customers);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);
        Customer::create($request->all());
        Log::info('Creación de usuario', ['IP' => request()->ip(), 'Datos' => $request->all()]);
        return $this->success();
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->successData($customer->select('name', 'last_name', 'address', 'description', 'id_reg','id_com'));
    }

    /**
     * @param Customer $customer
     * @param CustomerRequest $request
     * @return JsonResponse
     */
    public function update(Customer $customer, CustomerRequest $request): JsonResponse
    {
        $customer->update($request->all());

        Log::info('Actualización de usuario', ['IP' => request()->ip(), 'Datos' => $request->all()]);
        return $this->success();
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     */
    public function destroy(Customer $customer)
    {
        if($customer->status != 'trash'){
            $customer->update(['status'=> 'trash']);
            Log::info('Eliminación de usuario', ['IP' => request()->ip(), 'Datos' => $customer]);
            $customer->delete();
            return $this->success();
        }
        return $this->successMessage('Registro no existe');
    }
}
