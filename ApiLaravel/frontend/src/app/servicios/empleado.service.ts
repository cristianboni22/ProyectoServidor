import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, catchError, throwError, tap } from 'rxjs';
import { TipoEmpleado } from '../modelos/tipoEmpleado';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class EmpleadoService {
  private apiUrl = 'http://localhost:8000/api/empleado';

  listaEmpleados: TipoEmpleado[] = [];
  formularioEmpleado: TipoEmpleado = this.getEmpleadoVacio();

  modoCrearEmpleado = false;
  modoEditarEmpleado = false;
  mensajeErrorEmpleado = '';

  constructor(private http: HttpClient, private authService: AuthService) {}

  // CRUD

  getEmpleados(): Observable<TipoEmpleado[]> {
    return this.http.get<TipoEmpleado[]>(this.apiUrl, this.getHeaders()).pipe(
      tap(response => console.log('Respuesta del servicio getEmpleados:', response)),
      catchError(this.handleError)
    );
  }

  getEmpleadoByDni(dni: string): Observable<TipoEmpleado> {
    return this.http.get<TipoEmpleado>(`${this.apiUrl}/${dni}`, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  crearEmpleado(empleado: TipoEmpleado): Observable<any> {
    return this.http.post(this.apiUrl, empleado, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  actualizarEmpleado(dni: string, empleado: TipoEmpleado): Observable<any> {
    return this.http.put(`${this.apiUrl}/${dni}`, empleado, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  eliminarEmpleado(dni: string): void {
    this.http.delete(`${this.apiUrl}/${dni}`, this.getHeaders()).subscribe({
      next: (response) => {
        console.log('Empleado eliminado:', response);
        this.recargarListaYLimpiar();
      },
      error: (err) => {
        console.error('Error al eliminar empleado:', err.message);
        this.mensajeErrorEmpleado = err.message;
      }
    });
  }

  // Acciones desde el formulario

  guardarEmpleado(): void {
    this.mensajeErrorEmpleado = '';

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
    this.getEmpleadoByDni(dni).subscribe({
      next: (empleado) => {
        this.formularioEmpleado = { ...empleado };
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
    this.limpiarFormulario();
    this.modoCrearEmpleado = true;
    this.modoEditarEmpleado = false;
    this.mensajeErrorEmpleado = '';
  }

  obtenerEmpleados(): void {
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
    this.formularioEmpleado = this.getEmpleadoVacio();
    this.modoCrearEmpleado = false;
    this.modoEditarEmpleado = false;
  }

  private recargarListaYLimpiar(): void {
    this.obtenerEmpleados();
    this.limpiarFormulario();
  }

  private getEmpleadoVacio(): TipoEmpleado {
    return {
      dni: '',
      nombre_completo: '',
      login: '',
      password: '',
      departamento_id: 0
    };
  }

  private getHeaders(): { headers: HttpHeaders } {
    const token = this.authService.getToken();
    return {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      })
    };
  }

  private handleError(error: any): Observable<never> {
    let errorMessage = 'Error inesperado. Intente más tarde.';
    console.error('Error en el servicio de empleados:', error);

    if (error.status === 403) {
      errorMessage = error.error?.error || 'No tienes permisos para realizar esta acción.';
    } else if (error.status === 404) {
      errorMessage = error.error?.message || 'No se encontró el recurso solicitado.';
    } else if (error.status === 400 || error.status === 422) {
      const errores = error.error?.error;
      if (errores && typeof errores === 'object') {
        errorMessage = Object.entries(errores)
          .map(([campo, mensaje]) => `${campo}: ${mensaje}`)
          .join('\n');
      } else {
        errorMessage = error.error?.message || 'Datos inválidos.';
      }
    }

    return throwError(() => new Error(errorMessage));
  }
}
