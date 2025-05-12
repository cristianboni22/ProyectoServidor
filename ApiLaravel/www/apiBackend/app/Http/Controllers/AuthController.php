<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Importa la clase Request para manejar las peticiones HTTP.
use App\Models\Empleado; // Importa el modelo Empleado, que probablemente representa la tabla de empleados en la base de datos.
use Illuminate\Support\Facades\Hash; // Importa la clase Hash para el cifrado de contraseñas.
use Illuminate\Support\Facades\Auth; // Importa la clase Auth para la autenticación de usuarios (aunque no se usa explícitamente en este código).
use Illuminate\Support\Facades\Validator; // Importa la clase Validator para la validación de datos.


class AuthController extends Controller
{
    // Este es el controlador para la autenticación de usuarios (registro e inicio de sesión).

    public function register(Request $request)
    {
        // Esta función maneja el registro de un nuevo empleado.
        // Recibe una instancia de Request que contiene los datos enviados en la petición.

        $validator = Validator::make($request->all(), [
            // Se definen las reglas de validación para los campos del formulario de registro.
            'dni' => [
                'required', // El campo 'dni' es obligatorio.
                'unique:empleado,dni', // El valor del 'dni' debe ser único en la columna 'dni' de la tabla 'empleado'.
                'regex:/^\d{7,9}[A-Z]$/i' // El 'dni' debe coincidir con una expresión regular (7 a 9 dígitos seguidos de una letra). La 'i' al final hace que la coincidencia no distinga entre mayúsculas y minúsculas.
            ],
            'nombre_completo' => 'required|string|max:100', // El 'nombre_completo' es obligatorio, debe ser una cadena de texto y no puede tener más de 100 caracteres.
            'login' => [
                'required', // El 'login' es obligatorio.
                'unique:empleado,login', // El valor del 'login' debe ser único en la columna 'login' de la tabla 'empleado'.
                'regex:/^[a-zA-Z0-9_]{4,20}$/'  // El 'login' debe coincidir con una expresión regular (solo letras, números y guiones bajos, con una longitud de 4 a 20 caracteres).
            ],
            'password' => [
                'required', // La 'password' es obligatoria.
                'min:8'         // La 'password' debe tener al menos 8 caracteres.
            ],
            'departamento_id' => 'required|exists:departamento,id', // El 'departamento_id' es obligatorio y debe existir como un 'id' en la tabla 'departamento'.
        ]);
        if ($validator->fails()) {
            // Si la validación falla, se devuelve una respuesta JSON con los errores y un código de estado HTTP 422 (Unprocessable Entity).
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }


        // Verificar si el login es "superadmin" (ignorar mayúsculas/minúsculas)
        if (strcasecmp(trim($request->login), 'superadmin') === 0) {
            // Si el login, después de eliminar espacios en blanco al principio y al final, es igual a "superadmin" (sin importar mayúsculas o minúsculas), se devuelve un error.
            return response()->json(['error' => ['login' => 'No se puede usar "SuperAdmin" como login.']], 400);
        }
        if (strcasecmp(trim($request->nombre_completo), 'superadmin') === 0) {
            // Si el nombre completo, después de eliminar espacios en blanco, es igual a "superadmin" (sin importar mayúsculas o minúsculas), se devuelve un error.
            return response()->json(['error' => ['nombre_completo' => 'No se puede usar "SuperAdmin" como nombre completo.']], 400);
        }
        if (strcasecmp(trim($request->dni), 'superadmin') === 0) {
            // Si el DNI, después de eliminar espacios en blanco, es igual a "superadmin" (sin importar mayúsculas o minúsculas), se devuelve un error.
            return response()->json(['error' => ['dni' => 'No se puede usar "SuperAdmin" como DNI.']], 400);
        }


        $empleado = Empleado::create([
            // Se crea un nuevo registro en la tabla 'empleado' con los datos proporcionados en la petición.
            'login' => $request->login, // Asigna el valor del 'login' de la petición.
            'password' => Hash::make($request->password), // Cifra la contraseña utilizando el algoritmo de hash predeterminado.
            'dni' => $request->dni, // Asigna el valor del 'dni' de la petición.
            'nombre_completo' => $request->nombre_completo, // Asigna el valor del 'nombre_completo' de la petición.
            'departamento_id' => $request->departamento_id, // Asigna el valor del 'departamento_id' de la petición.
        ]);

        if (!$empleado) {
            // Si la creación del empleado falla (por alguna razón), se devuelve un error con un código de estado HTTP 404 (Not Found), aunque un 500 (Internal Server Error) podría ser más apropiado para un fallo en la creación.
            return response()->json(['error' => 'Problema con el Usuario'], 404);
        }
        // Si el empleado se crea exitosamente, se devuelve una respuesta JSON indicando el éxito.
        return response()->json(['respuesta' => 'El usuario ha sido creado con exito en la BD']);
    }

    public function login(Request $request)
    {
        // Esta función maneja el inicio de sesión de un empleado.
        // Recibe una instancia de Request con las credenciales del usuario.

        // Validar los datos recibidos
        $request->validate([
            'login' => 'required|string', // El 'login' es obligatorio y debe ser una cadena de texto.
            'password' => 'required|string' // La 'password' es obligatoria y debe ser una cadena de texto.
        ]);

        // Buscar al empleado por su login
        $empleado = Empleado::where('login', $request->login)->first();
        // Se busca en la tabla 'empleado' un registro donde la columna 'login' coincida con el valor proporcionado en la petición. 'first()' devuelve el primer registro encontrado o null si no se encuentra ninguno.

        // Si no se encuentra el empleado
        if (!$empleado) {
            // Si no se encuentra ningún empleado con el 'login' proporcionado, se devuelve un error con un código de estado HTTP 404 (Not Found).
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verificar si la contraseña es correcta
        if (!Hash::check($request->password, $empleado->password)) {
            // Se compara la contraseña proporcionada en la petición (sin cifrar) con la contraseña cifrada almacenada en la base de datos para el empleado encontrado. 'Hash::check()' realiza esta comparación de forma segura.
            // Si las contraseñas no coinciden, se devuelve un error con un código de estado HTTP 401 (Unauthorized).
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }
        // Aquí decides si el login pertenece a un SuperAdmin
        $role = ($empleado->login === 'SuperAdmin') ? 'SuperAdmin' : 'Empleado';
        // Se determina el rol del usuario. Si el 'login' del empleado es exactamente 'SuperAdmin', se le asigna el rol 'SuperAdmin', de lo contrario, se le asigna el rol 'Empleado'.

        // Crear el token de autenticación
        $token = $empleado->createToken('token-name');
        // Se crea un nuevo token de autenticación para el empleado utilizando el método 'createToken' proporcionado por Laravel Passport (o similar). 'token-name' es un nombre descriptivo para el token.

        // Responder con el token y el rol
        return response()->json([
            'mensaje' => $token->plainTextToken, // Se devuelve el token de autenticación en texto plano. Este token se utilizará para autenticar futuras peticiones.
            'role' => $role, // Se devuelve el rol del usuario.
            'login' => $empleado->login,
            'dni' => $empleado->dni
        ]);
    }
}