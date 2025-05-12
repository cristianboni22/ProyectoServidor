import { Injectable } from '@angular/core'; // Importa el decorador Injectable, que marca la clase como un servicio que puede ser inyectado.
import { HttpClient, HttpHeaders } from '@angular/common/http'; // Importa HttpClient para realizar peticiones HTTP y HttpHeaders para definir las cabeceras de las peticiones.
import { Observable, catchError, throwError, tap } from 'rxjs'; // Importa Observable para manejar operaciones asíncronas, catchError para manejar errores, throwError para crear observables de error y tap para ejecutar código como depuración.
import { TipoEmpleado } from '../modelos/tipoEmpleado'; // Importa la interfaz/tipo TipoEmpleado, que define la estructura de los datos de un empleado.
import { AuthService } from './auth.service'; // Importa el servicio AuthService, que probablemente maneja la autenticación.

@Injectable({
  providedIn: 'root' // Especifica que el servicio está disponible en toda la aplicación.
})
export class EmpleadoService {
  private apiUrl = 'http://localhost:8000/api/empleado'; // Define la URL base de la API para los endpoints de empleados.

  listaEmpleados: TipoEmpleado[] = []; // Declara un array para almacenar la lista de empleados.
  formularioEmpleado: TipoEmpleado = this.getEmpleadoVacio(); // Declara una variable para almacenar los datos del formulario del empleado, inicializada con un empleado vacío.

  modoCrearEmpleado = false; // Indica si el formulario está en modo de creación.
  modoEditarEmpleado = false; // Indica si el formulario está en modo de edición.
  mensajeErrorEmpleado = ''; // Almacena mensajes de error relacionados con las operaciones de empleados.

  constructor(private http: HttpClient, private authService: AuthService) {} // Constructor que inyecta las dependencias HttpClient y AuthService.

  // CRUD

  getEmpleados(): Observable<TipoEmpleado[]> {
    // Obtiene todos los empleados desde la API.
    return this.http.get<TipoEmpleado[]>(this.apiUrl, this.getHeaders()).pipe(
      tap(response => console.log('Respuesta del servicio getEmpleados:', response)), // Para ver la respuesta
      catchError(this.handleError) // Maneja los errores utilizando el método handleError.
    );
  }

  getEmpleadoByDni(dni: string): Observable<TipoEmpleado> {
    // Obtiene un empleado por su DNI desde la API.
    return this.http.get<TipoEmpleado>(`${this.apiUrl}/${dni}`, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  crearEmpleado(empleado: TipoEmpleado): Observable<any> {
    // Crea un nuevo empleado en la API.
    return this.http.post(this.apiUrl, empleado, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  actualizarEmpleado(dni: string, empleado: TipoEmpleado): Observable<any> {
    // Actualiza un empleado existente en la API.
    return this.http.put(`${this.apiUrl}/${dni}`, empleado, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  eliminarEmpleado(dni: string): void {
    // Elimina un empleado de la API.
    this.http.delete(`${this.apiUrl}/${dni}`, this.getHeaders()).subscribe({
      next: (response) => {
        console.log('Empleado eliminado:', response);
        this.recargarListaYLimpiar(); // Recarga la lista de empleados y limpia el formulario después de la eliminación.
      },
      error: (err) => {
        console.error('Error al eliminar empleado:', err.message);
        this.mensajeErrorEmpleado = err.message; // Asigna el mensaje de error para mostrarlo en la UI.
      }
    });
  }

  // Acciones desde el formulario

  guardarEmpleado(): void {
    // Guarda un empleado, ya sea creando uno nuevo o actualizando uno existente, dependiendo del modo del formulario.
    this.mensajeErrorEmpleado = ''; // Limpia cualquier mensaje de error anterior.

    if (this.modoCrearEmpleado) {
      this.crearEmpleado(this.formularioEmpleado).subscribe({
        next: (response) => {
          console.log('Empleado creado correctamente:', response);
          this.recargarListaYLimpiar();
        },
        error: (err) => {
          console.error('Error al crear empleado:', err.message);
          this.mensajeErrorEmpleado = err.message;
        }
      });
    } else if (this.modoEditarEmpleado && this.formularioEmpleado.dni) {
      this.actualizarEmpleado(this.formularioEmpleado.dni, this.formularioEmpleado).subscribe({
        next: (response) => {
          console.log('Empleado actualizado correctamente:', response);
          this.recargarListaYLimpiar();
        },
        error: (err) => {
          console.error('Error al actualizar empleado:', err.message);
          this.mensajeErrorEmpleado = err.message;
        }
      });
    }
  }

  editarEmpleado(dni: string): void {
    // Carga los datos de un empleado en el formulario para su edición.
    this.getEmpleadoByDni(dni).subscribe({
      next: (empleado) => {
        this.formularioEmpleado = { ...empleado }; // Copia los datos del empleado al formulario.
        this.modoEditarEmpleado = true;
        this.modoCrearEmpleado = false;
        this.mensajeErrorEmpleado = '';
      },
      error: (err) => {
        console.error('Error al cargar empleado:', err.message);
        this.mensajeErrorEmpleado = err.message;
      }
    });
  }

  nuevoEmpleado(): void {
    // Prepara el formulario para la creación de un nuevo empleado.
    this.limpiarFormulario();
    this.modoCrearEmpleado = true;
    this.modoEditarEmpleado = false;
    this.mensajeErrorEmpleado = '';
  }

  obtenerEmpleados(): void {
    // Obtiene la lista de empleados y la almacena en la propiedad listaEmpleados.
    this.getEmpleados().subscribe({
      next: (empleados) => {
        this.listaEmpleados = empleados;
        console.log('Lista de empleados obtenida:', this.listaEmpleados);
      },
      error: (err) => {
        console.error('Error al obtener empleados:', err.message);
        this.mensajeErrorEmpleado = err.message;
      }
    });
  }

  // Utilidades

  limpiarFormulario(): void {
    // Resetea el formulario a su estado vacío.
    this.formularioEmpleado = this.getEmpleadoVacio();
    this.modoCrearEmpleado = false;
    this.modoEditarEmpleado = false;
  }

  private recargarListaYLimpiar(): void {
    // Método privado para recargar la lista de empleados y limpiar el formulario.
    this.obtenerEmpleados();
    this.limpiarFormulario();
  }

  private getEmpleadoVacio(): TipoEmpleado {
    // Devuelve un objeto TipoEmpleado vacío.
    return {
      dni: '',
      nombre_completo: '',
      login: '',
      password: '',
      departamento_id: 0
    };
  }

  private getHeaders(): { headers: HttpHeaders } {
    // Devuelve las cabeceras HTTP necesarias para la autenticación.
    const token = this.authService.getToken(); // Obtiene el token de autenticación del servicio AuthService.
    return {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}` // Incluye el token en la cabecera de autorización.
      })
    };
  }

  private handleError(error: any): Observable<never> {
    // Maneja los errores HTTP de manera centralizada.
    let errorMessage = 'Error inesperado. Intente más tarde.';
    console.error('Error en el servicio de empleados:', error);

    if (error.status === 403) {
      errorMessage = error.error?.error || 'No tienes permisos para realizar esta acción.'; // Personaliza el mensaje para errores de autorización (403).
    } else if (error.status === 404) {
      errorMessage = error.error?.message || 'No se encontró el recurso solicitado.'; // Personaliza el mensaje para errores de "no encontrado" (404).
    } else if (error.status === 400 || error.status === 422) {
      // Personaliza el mensaje para errores de datos inválidos (400, 422).
      const errores = error.error?.error;
      if (errores && typeof errores === 'object') {
        errorMessage = Object.entries(errores)
          .map(([campo, mensaje]) => `${campo}: ${mensaje}`)
          .join('\n'); // Formatea los errores de validación del servidor.
      } else {
        errorMessage = error.error?.message || 'Datos inválidos.';
      }
    }

    return throwError(() => new Error(errorMessage)); // Lanza un Observable de error con el mensaje personalizado.
  }
}

