<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración Predeterminada de Autenticación
    |--------------------------------------------------------------------------
    |
    | Esta opción define el "guard" de autenticación predeterminado y el
    | "broker" de restablecimiento de contraseña para tu aplicación.
    | Puedes cambiar estos valores según sea necesario, pero son un inicio
    | perfecto para la mayoría de las aplicaciones.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),         // El guard predeterminado a utilizar para la autenticación. Por defecto es 'web' si la variable de entorno AUTH_GUARD no está establecida.
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'), // El broker de restablecimiento de contraseña predeterminado. Por defecto es 'users'.
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de Autenticación
    |--------------------------------------------------------------------------
    |
    | A continuación, puedes definir cada guard de autenticación para tu aplicación.
    | Por supuesto, se ha definido una gran configuración predeterminada para ti
    | que utiliza el almacenamiento de sesión más el proveedor de usuario Eloquent.
    |
    | Todos los guards de autenticación tienen un proveedor de usuario, que define cómo
    | los usuarios son realmente recuperados de tu base de datos u otro sistema de
    | almacenamiento utilizado por la aplicación. Por lo general, se utiliza Eloquent.
    |
    | Soportado: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',  // El driver para el guard 'web'. Utiliza el almacenamiento de sesión.
            'provider' => 'empleados', // El proveedor de usuario para el guard 'web'. Especifica cómo recuperar los datos del usuario (en este caso, del proveedor 'empleados').
        ],

        'api' => [
            'driver' => 'sanctum',  // El driver para el guard 'api'. Utiliza Laravel Sanctum para la autenticación de la API.
            'provider' => 'empleados', // El proveedor de usuario para el guard 'api'. También utiliza el proveedor 'empleados'.
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Proveedores de Usuario
    |--------------------------------------------------------------------------
    |
    | Todos los guards de autenticación tienen un proveedor de usuario, que define cómo
    | los usuarios son realmente recuperados de tu base de datos u otro sistema de
    | almacenamiento utilizado por la aplicación. Por lo general, se utiliza Eloquent.
    |
    | Si tienes múltiples tablas de usuarios o modelos, puedes configurar múltiples
    | proveedores para representar el modelo / tabla. Estos proveedores pueden ser
    | asignados a cualquier guard de autenticación adicional que hayas definido.
    |
    | Soportado: "database", "eloquent"
    |
    */

    'providers' => [
        'empleados' => [
            'driver' => 'eloquent', // El driver para el proveedor 'empleados'. Utiliza Eloquent (ORM).
            'model' => env('AUTH_MODEL', App\Models\Empleado::class), // El modelo Eloquent a utilizar para el proveedor 'empleados'.
                                                                   // Por defecto es App\Models\Empleado si la variable de entorno AUTH_MODEL no está establecida.
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Restablecimiento de Contraseñas
    |--------------------------------------------------------------------------
    |
    | Estas opciones de configuración especifican el comportamiento de la funcionalidad
    | de restablecimiento de contraseñas de Laravel, incluyendo la tabla utilizada para el
    | almacenamiento de tokens y el proveedor de usuario que se invoca para recuperar
    | realmente a los usuarios.
    |
    | El tiempo de expiración es el número de minutos que cada token de restablecimiento
    | se considerará válido. Esta característica de seguridad mantiene los tokens de corta
    | duración para que tengan menos tiempo de ser adivinados. Puedes cambiar esto según
    | sea necesario.
    |
    | La configuración de "throttle" es el número de segundos que un usuario debe esperar
    | antes de generar más tokens de restablecimiento de contraseña. Esto evita que el
    | usuario genere rápidamente una gran cantidad de tokens de restablecimiento de
    | contraseña.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users', // El proveedor de usuario a utilizar para los restablecimientos de contraseña.
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'), // La tabla de la base de datos para almacenar los tokens de restablecimiento de contraseña.
            'expire' => 60,       // El número de minutos que el token de restablecimiento es válido.
            'throttle' => 60,     // El número de segundos a esperar antes de que un usuario pueda solicitar otro restablecimiento de contraseña.
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de Espera de Confirmación de Contraseña
    |--------------------------------------------------------------------------
    |
    | Aquí puedes definir la cantidad de segundos antes de que expire una ventana de
    | confirmación de contraseña y se pida a los usuarios que vuelvan a introducir su
    | contraseña a través de la pantalla de confirmación. Por defecto, el tiempo de
    | espera dura tres horas.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800), // El tiempo de espera en segundos para la confirmación de la contraseña. Por defecto es 10800 (3 horas).
];
