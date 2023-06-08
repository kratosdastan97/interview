<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function login($email, $password)
    {
        // Verificar las credenciales del usuario
        if ($this->verificarCredenciales($email, $password)) {
            // Generar el token con fecha de vencimiento
            $token = $this->generarToken($email, '+1 hour'); // Token válido por 1 hora

            // Devolver el token
            return $token;
        } else {
            // Devolver error de autenticación
            return 'Error de autenticación';
        }
    }

    public function verificarCredenciales($email, $password)
    {
        return true;
    }

    public function generarToken($email, $expiracion)
    {
        // Obtener la fecha y hora actual
        $fechaHora = date('Y-m-d H:i:s');

        // Generar un número aleatorio entre 200 y 500
        $random = mt_rand(200, 500);

        // Concatenar el email, fecha y hora, y el número aleatorio
        $tokenData = $email . $fechaHora . $random;

        // Encriptar el token con SHA-1
        $token = sha1($tokenData);

        // Obtener la fecha y hora de vencimiento
        $fechaHoraVencimiento = date('Y-m-d H:i:s', strtotime($expiracion));

        // Agregar la fecha y hora de vencimiento al token
        $token .= '|' . $fechaHoraVencimiento;

        return $token;
    }

// Función para verificar si el token ha expirado
    function verificarTokenExpirado($token)
    {
        // Obtener la fecha y hora actual
        $fechaHoraActual = date('Y-m-d H:i:s');

        // Obtener la fecha y hora de vencimiento del token
        $tokenParts = explode('|', $token);
        $fechaHoraVencimiento = $tokenParts[1];

        // Verificar si el token ha expirado
        if ($fechaHoraActual > $fechaHoraVencimiento) {
            return true; // Token expirado
        } else {
            return false; // Token válido
        }
    }
}
