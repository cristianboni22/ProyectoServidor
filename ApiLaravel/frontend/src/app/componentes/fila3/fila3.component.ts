import { Component, OnInit } from '@angular/core'; // Para definir el componente y usar el ciclo de vida OnInit
import { FormsModule } from '@angular/forms'; // Para usar formularios con ngModel
import { CommonModule } from '@angular/common'; // Módulo común de Angular (ngIf, ngFor, etc.)
import { EmpleadoService } from '../../servicios/empleado.service'; // Servicio que maneja lógica de empleados
import { DepartamentosService } from '../../servicios/departamentos.service'; // Servicio para departamentos
import { TipoDepartamento } from '../../modelos/tipoDepartamento'; // Modelo para definir la estructura de un departamento

@Component({
  selector: 'app-fila3', // Selector del componente (etiqueta que se usa en HTML)
  standalone: true, // Indica que es un componente independiente (sin necesidad de un módulo)
  imports: [FormsModule, CommonModule], // Módulos necesarios para usar ngModel, ngIf, etc.
  templateUrl: './fila3.component.html', // HTML asociado al componente
  styleUrls: ['./fila3.component.css'] // CSS del componente
})
export class Fila3Component implements OnInit {
  login: string = ''; // Guarda el login del usuario actual
  role: string = ''; // Guarda el rol del usuario actual (SuperAdmin o Empleado)
  listaDepartamentos: TipoDepartamento[] = []; // Lista de departamentos que se carga al iniciar

  constructor(
    public servicio: EmpleadoService, // Inyección del servicio de empleados
    public departamentoService: DepartamentosService // Inyección del servicio de departamentos
  ) { }

  modoEdicion: boolean = false; // Bandera para saber si se está editando algo

  ngOnInit(): void {
    // Se llama al cargar el componente

    this.servicio.obtenerEmpleados(); // Se cargan los empleados desde el servicio
    this.departamentoService.obtenerDepartamentos(); // Se hace la petición para cargar los departamentos
    //this.listaDepartamentos = this.departamentoService.listaDepartamentos;
    // Se obtiene la lista de departamentos del observable
    //this.departamentoService.getDepartamentos().subscribe(data => {
    //this.listaDepartamentos = data; // Se guarda la lista en la variable del componente
    //});

    // Se recupera el login del sessionStorage
    this.login = sessionStorage.getItem('login') || '';

    // Se recupera el rol del usuario. Si no hay nada, por defecto es "Empleado"
    const rawRole = sessionStorage.getItem('role') || 'Empleado';
    this.role = rawRole.trim(); // Se eliminan espacios extra por seguridad

    // Se muestra en consola el rol que tiene el usuario
    console.log('ROL ACTUAL:', this.role);

  }

  // Función para que Angular sepa cómo hacer seguimiento (trackBy) de los empleados por su DNI
  trackByDni(index: number, empleado: any): string {
    return empleado.dni;
  }

  // Función para seguimiento de departamentos por su ID
  trackById(index: number, departamento: any): string {
    return departamento.id;
  }

  // Función para obtener el nombre de un departamento a partir de su ID
  getDepartamentoName(departamentoId: number): string {
    const departamento = this.departamentoService.listaDepartamentos.find(dep => dep.id === departamentoId);
    return departamento ? departamento.nombre : 'Desconocido';
  }
}