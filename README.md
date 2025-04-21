# Sistema de Gestión de Empleados

Este proyecto está dividido en dos partes principales:  
- **Backend:** desarrollado con Laravel (PHP).
- **Frontend:** desarrollado con Angular (TypeScript).

## 🧠 Descripción general

El sistema permite la gestión de **empleados** y su relación con los **departamentos** dentro de una organización.  
A través del frontend en Angular, se pueden realizar operaciones como registrar nuevos empleados, asignarlos a departamentos, y visualizar los datos disponibles.

---

## 🖥️ Backend - Laravel

El backend está construido con Laravel y se encarga de manejar toda la lógica de negocio, validaciones, autenticación (JWT), y persistencia de datos en la base de datos MySQL.

### Modelos principales:

#### 🧑‍💼 Empleado
- Representa a un trabajador de la organización.
- Atributos:
  - `dni` (clave primaria)
  - `login`
  - `password` (encriptada)
  - `nombre_completo`
  - `departamento_id` (relación con Departamento)

#### 🏢 Departamento
- Representa un área funcional de la empresa.
- Atributos:
  - `id`
  - `nombre`
  - `telefono`
  - `email`

#### 🔐 Autenticación
- El sistema usa **JWT** para autenticar usuarios a través de tokens.
- Las rutas protegidas requieren que el usuario esté autenticado para operar sobre los datos de empleados y departamentos.

---

## 🌐 Frontend - Angular

El frontend consume la API REST creada en Laravel y permite al usuario interactuar con el sistema a través de una interfaz web.

### Funcionalidades:
- Iniciar sesión como empleado.
- Registrar nuevos empleados (si se tiene permisos).
- Listar empleados y departamentos.
- Asignar un empleado a un departamento.
- Visualización amigable y dinámica con Angular.

---

## 🚀 Requisitos para correr el proyecto

### Backend:
- PHP ^8.1
- Composer
- MySQL o MariaDB
- Laravel ^10

### Frontend:
- Node.js ^19
- Angular CLI

---

## ⚙️ Instrucciones para ejecutar

### Backend
```bash
cd apiBackend
composer install
php artisan migrate --seed
php artisan serve
```

### Frontend
```bash
cd frontend 
npm build
npm install
ng serve --host 0.0.0.0 --port 4200
```


Necesito autenticar las peticiones de las rutas del backend con el token desde el frontend 

frontend si es un usuario no administrador solo muestra el mismo propio y los datos del departamento 

Mostrar los errores que te da el backend si falta algo o si repite algo

Puedo añadir al modelo de empleado una columna llamada rol y que diga si es SuperAdmin o empleado o sino de otra manera

