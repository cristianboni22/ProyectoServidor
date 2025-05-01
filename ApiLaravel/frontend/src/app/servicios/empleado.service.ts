import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { TipoEmpleado } from '../modelos/tipoEmpleado';

@Injectable({
  providedIn: 'root'
})
export class EmpleadoService {

  // 🌐 URL base de la API
  private apiUrl = 'http://localhost:8000/api/empleado';

  // 📦 Estado de la app
  listaEmpleados: TipoEmpleado[] = [];
  formularioEmpleado = {
    dni: '',
    nombre_completo: '',
    login: '',
    password: '',
    departamento_id: ''
  };
  modoEdicion: boolean = false;

  constructor(private http: HttpClient) {}

  // 🚀 MÉTODOS CRUD

  // 🔄 Obtener todos los empleados
  getEmpleados(): Observable<TipoEmpleado[]> {
    return this.http.get<TipoEmpleado[]>(this.apiUrl);
  }

  // 🔍 Obtener un empleado por DNI
  getEmpleadoByDni(dni: string): Observable<TipoEmpleado> {
    return this.http.get<TipoEmpleado>(`${this.apiUrl}/${dni}`);
  }


  // ➕ Crear un nuevo empleado
  crearEmpleado(): void {// clonar
    this.http.post(this.apiUrl, this.formularioEmpleado, {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' })
    }).subscribe(() => {
      this.obtenerEmpleados();
      this.limpiarFormulario();
    });
  }

  // ✏️ Editar (cargar datos al formulario)
  editarEmpleado(dni: string): void {
    const empleado = this.listaEmpleados.find(emp => emp.dni === dni);
    if (empleado) {
      this.formularioEmpleado = { ...empleado };
      this.modoEdicion = true;
    }
  }

  // ✅ Actualizar empleado
  actualizarEmpleado(): void {
    this.http.put(`${this.apiUrl}/${this.formularioEmpleado.dni}`, this.formularioEmpleado)
      .subscribe({
        next: () => {
          alert('Empleado actualizado correctamente');
          this.obtenerEmpleados();
          this.limpiarFormulario();
        },
        error: (error) => {
          alert('Error al actualizar el empleado');
          console.error('Error al actualizar empleado:', error);
        }
      });
  }

  // 🗑️ Eliminar empleado
  eliminarEmpleado(dni: string): void {
    if (confirm('¿Estás seguro de eliminar este empleado?')) {
      this.http.delete(`${this.apiUrl}/${dni}`)
        .subscribe({
          next: () => {
            alert('Empleado eliminado correctamente');
            this.obtenerEmpleados();
          },
          error: (error) => {
            alert('Error al eliminar el empleado');
            console.error('Error al eliminar empleado:', error);
          }
        });
    }
  }

  // 📥 Recargar empleados a la lista local
  obtenerEmpleados(): void {
    this.http.get<TipoEmpleado[]>(this.apiUrl)
      .subscribe({
        next: (data) => {
          this.listaEmpleados = data;
        },
        error: (error) => {
          console.error('Error al obtener empleados:', error);
        }
      });
  }

  // 🧼 Limpiar formulario y reiniciar modo edición
  limpiarFormulario(): void {
    this.formularioEmpleado = {
      dni: '',
      nombre_completo: '',
      login: '',
      password: '',
      departamento_id: ''
    };
    this.modoEdicion = false;
  }

  // ✨ Inicializar nuevo empleado
  nuevoEmpleado(): void {
    console.log("Clic en nuevo empleado");
    this.formularioEmpleado = {
      dni: '',
      nombre_completo: '',
      login: '',
      password: '',
      departamento_id: ''
    };
    this.modoEdicion = true;
  }
  
  guardarEmpleado(): void {
    if (this.formularioEmpleado.dni) {
      this.crearEmpleado(); 
    } else {
      this.actualizarEmpleado(); 
    }
  }
  
}
