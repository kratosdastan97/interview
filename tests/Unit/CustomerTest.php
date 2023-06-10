<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
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

        $customerData = [
            'dni' => '123456789',
            // Resto de los campos
        ];

        $response = $this->post('/customers', $customerData);
// Esperas recibir un mensaje de error indicando que el DNI ya existe en la tabla


        // Obtener los clientes activos
        $activeCustomers = Customer::active()->get();

        $this->assertCount(2, $activeCustomers);

        foreach ($activeCustomers as $customer) {
            $this->assertEquals('A', $customer->status);
        }
    }

    public function testDuplicatedDni()
    {
        // Crea un registro de ejemplo en la base de datos con un DNI existente
        $existingCustomer = [
            'dni' => '123456789',
            'email' => 'existing@example.com',
            'id_reg' => 2,
            'id_com' => 2,
            'name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '456 Oak St',
            'date_reg' => now(),
            'status' => 'A',
        ];
        \App\Models\Customer::factory()->create($existingCustomer);

        // Intenta insertar un nuevo registro con el mismo DNI
        $newCustomerData = [
            'dni' => '123456789',
            'email' => 'john@example.com',
            'id_reg' => 2,
            'id_com' => 2,
            'name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '456 Oak St',
            'date_reg' => now(),
            'status' => 'A',
        ];

        $response = $this->post('/customers', $newCustomerData);

        // Verifica que la respuesta sea un error de duplicación de DNI
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['dni']);
    }

    public function withoutInputsRequired(){
        $customerData = [
            'name' => 'John',
            // Faltan otros campos requeridos
        ];

        $response = $this->post('/customers', $customerData);
        // Esperas recibir un mensaje de error indicando que faltan datos requeridos
        $response = $this->post('/customers', $customerData);
        $response->assertSessionHasErrors(['dni', 'email', 'id_reg', 'id_com', 'last_name', 'address', 'date_reg']);
        // Esperas recibir un mensaje de error indicando que faltan datos requeridos

    }

    public function regexValidation(){
        $customerData = [
            'dni' => 'ABC123', // El DNI debe contener solo dígitos
            'email' => 'john@example', // El email debe tener un formato válido
            'id_reg' => 2,
            'id_com' => 2,
            'name' => 'Jane',
            'last_name' => 'Smith',
            'address' => '456 Oak St',
            'date_reg' => now(),
            'status' => 'A',
        ];
        $response = $this->post('/customers', $customerData);
        // Esperas recibir un mensaje de error indicando que los datos no cumplen con el formato requerido
        $response->assertStatus(422); // Verifica que la respuesta sea un error de validación
        $response->assertJsonValidationErrors(['dni', 'email']); // Verifica que los campos 'dni' y 'email' tengan errores de validación

    }


}
