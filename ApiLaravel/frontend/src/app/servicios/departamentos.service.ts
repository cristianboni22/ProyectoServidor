import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { TipoDepartamento } from '../modelos/tipoDepartamento';

@Injectable({
  providedIn: 'root'
})
export class DepartamentosService {

  private apiUrl = 'http://localhost:8000/api/departamento';

  private actualizacionLista = new Subject<void>();
  actualizacionLista$ = this.actualizacionLista.asObservable();

  listaDepartamentos: TipoDepartamento[] = [];
  modoEdicion: boolean = false;

  formularioDepartamento: TipoDepartamento = {
    id: 0,
    nombre: '',
    telefono: '',
    email: ''
  };

  constructor(private http: HttpClient) {}

  private getAuthHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    });
  }

  getDepartamentos(): Observable<TipoDepartamento[]> {
    return this.http.get<TipoDepartamento[]>(this.apiUrl, {
      headers: this.getAuthHeaders()
    });
  }

  getDepartamentoById(id: number): Observable<TipoDepartamento> {
    return this.http.get<TipoDepartamento>(`${this.apiUrl}/${id}`, {
      headers: this.getAuthHeaders()
    });
  }

  crearDepartamento(): void {
    this.http.post(this.apiUrl, this.formularioDepartamento, {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: () => {
        this.obtenerDepartamentos();
        this.limpiarFormulario();
        alert('Departamento creado correctamente');
      },
      error: (err) => {
        alert('Error al crear departamento');
        console.error(err);
      }
    });
  }

  editarDepartamento(id: number): void {
    const departamento = this.listaDepartamentos.find(dep => dep.id === id);
    if (departamento) {
      this.formularioDepartamento = { ...departamento };
      this.modoEdicion = true;
    }
  }

  actualizarDepartamento(): void {
    this.http.put(`${this.apiUrl}/${this.formularioDepartamento.id}`, this.formularioDepartamento, {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: () => {
        alert('Departamento actualizado correctamente');
        this.obtenerDepartamentos();
        this.limpiarFormulario();
      },
      error: (error) => {
        alert('Error al actualizar el departamento');
        console.error(error);
      }
    });
  }

  eliminarDepartamento(id: number): void {
    if (confirm('¿Estás seguro de eliminar este departamento?')) {
      this.http.delete(`${this.apiUrl}/${id}`, {
        headers: this.getAuthHeaders()
      }).subscribe({
        next: () => {
          alert('Departamento eliminado correctamente');
          this.obtenerDepartamentos();
        },
        error: (error) => {
          alert('Error al eliminar el departamento');
          console.error(error);
        }
      });
    }
  }

  obtenerDepartamentos(): void {
    this.http.get<TipoDepartamento[]>(this.apiUrl, {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: (data) => {
        this.listaDepartamentos = data;
        console.log('Departamentos actualizados:', data);
      },
      error: (error) => {
        console.error('Error al obtener departamentos:', error);
      }
    });
  }

  limpiarFormulario(): void {
    this.formularioDepartamento = {
      id: 0,
      nombre: '',
      telefono: '',
      email: ''
    };
    this.modoEdicion = false;
  }

  nuevoDepartamento(): void {
    this.formularioDepartamento = {
      id: 0,
      nombre: '',
      telefono: '',
      email: ''
    };
    this.modoEdicion = true;
  }

  guardarDepartamento(): void {
    if (this.formularioDepartamento.id === 0) {
      this.crearDepartamento();
    } else {
      this.actualizarDepartamento();
    }
  }
}
