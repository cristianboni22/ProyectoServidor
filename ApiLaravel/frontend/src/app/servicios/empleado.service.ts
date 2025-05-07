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
  formularioEmpleado: TipoEmpleado = {
    dni: '',
    nombre_completo: '',
    login: '',
    password: '',
    departamento_id: 0
  };

  modoCrearEmpleado: boolean = false;
  modoEditarEmpleado: boolean = false;

  constructor(private http: HttpClient, private authService: AuthService) { }

  // Métodos CRUD mejorados con manejo de errores

  getEmpleados(): Observable<TipoEmpleado[]> {
    return this.http.get<TipoEmpleado[]>(this.apiUrl, this.getHeaders()).pipe(
      tap(response => {
        console.log('Respuesta del servicio getEmpleados:', response);
      }),
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
    return this.http.put<any>(`${this.apiUrl}/${dni}`, empleado, this.getHeaders()).pipe(
      catchError(this.handleError)
    );
  }

  guardarEmpleado(): void {
    if (this.modoCrearEmpleado) {
      this.crearEmpleado(this.formularioEmpleado).subscribe({
        next: (response) => {
          console.log('Empleado creado correctamente:', response);
          this.obtenerEmpleados();
          this.limpiarFormulario();
        },
        error: (err) => console.error('Error al crear empleado:', err)
      });
    } else if (this.modoEditarEmpleado && this.formularioEmpleado.dni) {
      this.actualizarEmpleado(this.formularioEmpleado.dni, this.formularioEmpleado).subscribe({
        next: (response) => {
          console.log('Empleado actualizado correctamente:', response);
          this.obtenerEmpleados();
          this.limpiarFormulario();
        },
        error: (err) => console.error('Error al actualizar empleado:', err)
      });
    }
  }

  eliminarEmpleado(dni: string): void {
    const url = `${this.apiUrl}/${dni}`;
    this.http.delete<any>(url).subscribe({
      next: (response) => {
        console.log('Empleado eliminado:', response);
        this.obtenerEmpleados();
        this.limpiarFormulario();
      },
      error: (error) => {
        console.error('Error al eliminar empleado:', error);
      }
    });
  }

  // Métodos para manejar el estado

  nuevoEmpleado(): void {
    this.limpiarFormulario();
    this.modoCrearEmpleado = true;
    this.modoEditarEmpleado = false;
  }

  editarEmpleado(dni: string): void {
    this.getEmpleadoByDni(dni).subscribe({
      next: (empleado) => {
        this.formularioEmpleado = { ...empleado };
        this.modoEditarEmpleado = true;
        this.modoCrearEmpleado = false;
      },
      error: (err) => console.error('Error al cargar empleado:', err)
    });
  }

  obtenerEmpleados(): void {
    this.getEmpleados().subscribe({
      next: (empleados) => {
        this.listaEmpleados = empleados;
        console.log('Lista de empleados obtenida:', this.listaEmpleados);
      },
      error: (err) => console.error('Error al obtener empleados:', err)
    });
  }

  limpiarFormulario(): void {
    this.formularioEmpleado = {
      dni: '',
      nombre_completo: '',
      login: '',
      password: '',
      departamento_id: 0
    };
    this.modoCrearEmpleado = false;
    this.modoEditarEmpleado = false;
  }

  // Métodos auxiliares

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
    console.error('Error en el servicio de empleados:', error);
    let errorMessage = 'Ocurrió un error';
    if (error.error && error.error.message) {
      errorMessage = error.error.message;
    }
    return throwError(() => new Error(errorMessage));
  }
}
