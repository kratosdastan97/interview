<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::active()->get('name', 'last_name', 'address', 'description', 'id_reg','id_com');
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $customer = Customer::create($request->all());
        Log::info('Creación de usuario', ['IP' => request()->ip(), 'Datos' => $request->all()]);
        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return response()->json($customer->select('name', 'last_name', 'address', 'description', 'id_reg','id_com'));
    }

    public function update( Customer $customer, CustomerRequest $request)
    {
        $customer->update($request->all());

        Log::info('Actualización de usuario', ['IP' => request()->ip(), 'Datos' => $request->all()]);
        return response()->json($customer, 200);
    }

    public function destroy(Customer $customer)
    {
        if($customer->status != 'trash'){
            $customer->update(['status'=> 'trash']);
            Log::info('Eliminación de usuario', ['IP' => request()->ip(), 'Datos' => $customer]);
            $customer->delete();
            return response()->json(null, 204);
        }
        return response()->json(['message' => 'Registro no existe'], 404);

    }
}
