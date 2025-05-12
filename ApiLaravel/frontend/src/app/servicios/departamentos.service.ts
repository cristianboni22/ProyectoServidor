import { Injectable } from '@angular/core';  // Importa el decorador Injectable.
import { HttpClient, HttpHeaders } from '@angular/common/http';  // Importa HttpClient y HttpHeaders.
import { Observable, Subject } from 'rxjs';  // Importa Observable y Subject de la librería RxJS.
import { TipoDepartamento } from '../modelos/tipoDepartamento';  // Importa la interfaz TipoDepartamento.

@Injectable({
  providedIn: 'root'  // Especifica que el servicio está disponible a nivel de toda la aplicación.
})
export class DepartamentosService {

  private apiUrl = 'http://localhost:8000/api/departamento';  // Define la URL base de la API para los departamentos.

  private actualizacionLista = new Subject<void>();  // Crea un Subject para notificar cambios en la lista de departamentos.
  actualizacionLista$ = this.actualizacionLista.asObservable();  // Expone un Observable para que los componentes se suscriban a las notificaciones de actualización.

  listaDepartamentos: TipoDepartamento[] = [];  // Declara un array para almacenar la lista de departamentos.
  modoEdicion: boolean = false;  // Indica si el formulario está en modo de edición.

  formularioDepartamento: TipoDepartamento = {  // Define la estructura del formulario para un departamento.
    id: 0,
    nombre: '',
    telefono: '',
    email: ''
  };

  constructor(private http: HttpClient) {}  // Constructor que inyecta el servicio HttpClient.

  private getAuthHeaders(): HttpHeaders {
    // Método privado para obtener las cabeceras de autenticación.
    const token = localStorage.getItem('token');  // Obtiene el token del almacenamiento local.
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`  // Incluye el token en la cabecera de Autorización.
    });
  }

  getDepartamentos(): Observable<TipoDepartamento[]> {
    // Obtiene todos los departamentos desde la API.
    return this.http.get<TipoDepartamento[]>(this.apiUrl, {
      headers: this.getAuthHeaders()  // Utiliza las cabeceras de autenticación.
    });
  }

  getDepartamentoById(id: number): Observable<TipoDepartamento> {
    // Obtiene un departamento por su ID desde la API.
    return this.http.get<TipoDepartamento>(`${this.apiUrl}/${id}`, {
      headers: this.getAuthHeaders()
    });
  }

  crearDepartamento(): void {
    // Crea un nuevo departamento en la API.
    this.http.post(this.apiUrl, this.formularioDepartamento, {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: () => {
        this.obtenerDepartamentos();  // Recarga la lista de departamentos.
        this.limpiarFormulario();  // Limpia el formulario.
        alert('Departamento creado correctamente');  // Muestra un mensaje de éxito.
        this.actualizacionLista.next();
      },
      error: (err) => {
        alert('Error al crear departamento');  // Muestra un mensaje de error.
        console.error(err);  // Imprime el error en la consola.
      }
    });
  }

  editarDepartamento(id: number): void {
    // Prepara el formulario para editar un departamento existente.
    const departamento = this.listaDepartamentos.find(dep => dep.id === id);  // Busca el departamento por su ID.
    if (departamento) {
      this.formularioDepartamento = { ...departamento };  // Copia los datos del departamento al formulario.
      this.modoEdicion = true;  // Establece el modo de edición.
    }
  }

  actualizarDepartamento(): void {
    // Actualiza un departamento existente en la API.
    this.http.put(`${this.apiUrl}/${this.formularioDepartamento.id}`, this.formularioDepartamento, {
      headers: this.getAuthHeaders()
    }).subscribe({
      next: () => {
        alert('Departamento actualizado correctamente');
        this.obtenerDepartamentos();
        this.limpiarFormulario();
        this.actualizacionLista.next();
      },
      error: (error) => {
        alert('Error al actualizar el departamento');
        console.error(error);
      }
    });
  }

  eliminarDepartamento(id: number): void {
    // Elimina un departamento de la API.
    if (confirm('¿Estás seguro de eliminar este departamento?')) {  // Muestra un mensaje de confirmación.
      this.http.delete(`${this.apiUrl}/${id}`, {
        headers: this.getAuthHeaders()
      }).subscribe({
        next: () => {
          alert('Departamento eliminado correctamente');
          this.obtenerDepartamentos();
          this.actualizacionLista.next();
        },
        error: (error) => {
          alert('Error al eliminar el departamento');
          console.error(error);
        }
      });
    }
  }

  obtenerDepartamentos(): void {
    // Obtiene la lista de departamentos desde la API y la almacena en la propiedad listaDepartamentos.
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
    // Resetea el formulario a su estado inicial.
    this.formularioDepartamento = {
      id: 0,
      nombre: '',
      telefono: '',
      email: ''
    };
    this.modoEdicion = false;
  }

  nuevoDepartamento(): void {
    // Prepara el formulario para la creación de un nuevo departamento.
    this.formularioDepartamento = {
      id: 0,
      nombre: '',
      telefono: '',
      email: ''
    };
    this.modoEdicion = true;
  }

  guardarDepartamento(): void {
    // Guarda un departamento, ya sea creando uno nuevo o actualizando uno existente.
    if (this.formularioDepartamento.id === 0) {
      this.crearDepartamento();  // Llama a crearDepartamento si es un nuevo departamento.
    } else {
      this.actualizarDepartamento();  // Llama a actualizarDepartamento si es una edición.
    }
  }
}

