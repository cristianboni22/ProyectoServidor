import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, Subject } from 'rxjs';
import { TipoDepartamento } from '../modelos/tipoDepartamento';

@Injectable({
  providedIn: 'root'
})
export class DepartamentosService {

  private apiUrl = 'http://localhost:8000/api/departamento';

  // 👉 Emitimos cambios a través de un Subject
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

  getDepartamentos(): Observable<TipoDepartamento[]> {
    return this.http.get<TipoDepartamento[]>(this.apiUrl);
  }

  getDepartamentoById(id: number): Observable<TipoDepartamento> {
    return this.http.get<TipoDepartamento>(`${this.apiUrl}/${id}`);
  }

  crearDepartamento(): void {
    this.http.post(this.apiUrl, this.formularioDepartamento, {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' })
    }).subscribe(() => {
      this.obtenerDepartamentos();  // ✅ Notifica al componente
      this.limpiarFormulario(); 
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
    this.http.put(`${this.apiUrl}/${this.formularioDepartamento.id}`, this.formularioDepartamento).subscribe({
      next: () => {
        alert('Departamento actualizado correctamente');
        this.obtenerDepartamentos(); // ✅ Notifica
        this.limpiarFormulario();
      },
      error: (error) => {
        alert('Error al actualizar el departamento');
        console.error('Error al actualizar departamento:', error);
      }
    });
  }

  eliminarDepartamento(id: number): void {
    if (confirm('¿Estás seguro de eliminar este departamento?')) {
      this.http.delete(`${this.apiUrl}/${id}`).subscribe({
        next: () => {
          alert('Departamento eliminado correctamente');
          this.obtenerDepartamentos(); // ✅ Notifica
        },
        error: (error) => {
          alert('Error al eliminar el departamento');
          console.error('Error al eliminar departamento:', error);
        }
      });
    }
  }

  obtenerDepartamentos(): void {
    this.http.get<TipoDepartamento[]>(this.apiUrl)
    .subscribe({
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
