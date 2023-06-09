<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    /**
     * Verificar que se puedan crear nuevos clientes.
     *
     * @return void
     */
    public function testCreateCustomer()
    {
        $customerData = [
            'dni' => '123456789',
            'id_reg' => 1,
            'id_com' => 1,
            'email' => 'example@example.com',
            'name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St',
            'date_reg' => now(),
            'status' => 'A',
        ];

        $customer = Customer::create($customerData);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('123456789', $customer->dni);
        $this->assertEquals('example@example.com', $customer->email);
    }

    /**
     * Verificar que se puedan obtener los clientes activos.
     *
     * @return void
     */
    public function testGetActiveCustomers()
    {
        // Crear algunos clientes activos
        Customer::create([
            'dni' => '123456789',
            'id_reg' => 1,
            'id_com' => 1,
            'email' => 'example1@example.com',
            'name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St',
            'date_reg' => now(),
            'status' => 'A',
        ]);

        Customer::create([
            'dni' => '987654321',
            'id_reg' => 2,
            'id_com' => 2,
            'email' => 'example2@example.com',
            'name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '456 Oak St',
            'date_reg' => now(),
            'status' => 'A',
        ]);

        // Crear algunos clientes inactivos
        Customer::create([
            'dni' => '111222333',
            'id_reg' => 3,
            'id_com' => 3,
            'email' => 'example3@example.com',
            'name' => 'Mike',
            'last_name' => 'Johnson',
            'address' => '789 Elm St',
            'date_reg' => now(),
            'status' => 'I',
        ]);

        // Obtener los clientes activos
        $activeCustomers = Customer::active()->get();

        $this->assertCount(2, $activeCustomers);

        foreach ($activeCustomers as $customer) {
            $this->assertEquals('A', $customer->status);
        }
    }
}
