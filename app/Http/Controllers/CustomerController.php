<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
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

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        Log::info('Actualización de usuario', ['IP' => request()->ip(), 'Datos' => $request->all()]);
        return response()->json($customer, 200);
    }

    public function destroy(Customer $customer)
    {
        Log::info('Eliminación de usuario', ['IP' => request()->ip(), 'Datos' => $customer]);
        $customer->delete();
        return response()->json(null, 204);
    }
}
