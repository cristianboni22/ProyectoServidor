import { Component, OnInit, OnDestroy } from '@angular/core';
import { EmpleadoService } from '../../servicios/empleado.service';
import { DepartamentosService } from '../../servicios/departamentos.service';
import { TipoDepartamento } from '../../modelos/tipoDepartamento';
import { TipoEmpleado } from '../../modelos/tipoEmpleado';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-fila3',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './fila3.component.html',
  styleUrls: ['./fila3.component.css']
})
export class Fila3Component implements OnInit, OnDestroy {
  login: string = '';  // Almacena el login del usuario.
  role: string = '';    // Almacena el rol del usuario ('SuperAdmin' o 'Empleado').
  dni: string = '';     // Almacena el DNI del usuario.
  listaDepartamentos: TipoDepartamento[] = [];  // Almacena la lista de departamentos.
  empleadoActual: TipoEmpleado | null = null;    // Almacena el empleado actual si el rol es 'Empleado'.
  private departamentoSubscription: Subscription | undefined; // Subscription para actualizar la lista de departamentos

  constructor(
    public servicio: EmpleadoService,       // Servicio para la gestión de empleados.
    public departamentoService: DepartamentosService  // Servicio para la gestión de departamentos.
  ) { }

  ngOnInit(): void {
    // Obtiene los datos de sesión del usuario.
    this.login = sessionStorage.getItem('login') || '';
    this.dni = sessionStorage.getItem('dni') || '';
    this.role = (sessionStorage.getItem('role') || 'Empleado').trim();  // Obtiene el rol, por defecto 'Empleado'.

    // Imprime en la consola el rol y el dni del usuario.
    console.log('ROL ACTUAL:', this.role);
    console.log('DNI ACTUAL:', this.dni);

    // Obtiene la lista de departamentos del servicio.
    this.departamentoService.obtenerDepartamentos();
    this.listaDepartamentos = this.departamentoService.listaDepartamentos;

    // Si el rol es 'SuperAdmin', obtiene la lista de empleados.
    if (this.role === 'SuperAdmin') {
      this.servicio.obtenerEmpleados();
    }

    // Se suscribe al observable actualizacionLista$ del servicio departamentoService.
    // Esto asegura que la lista de departamentos se actualice cuando haya cambios.
    this.departamentoSubscription = this.departamentoService.actualizacionLista$.subscribe(() => {
      this.listaDepartamentos = this.departamentoService.listaDepartamentos;
      console.log('Lista de departamentos en Fila3Component (actualización):', this.listaDepartamentos);
    });

    // Si el rol es 'Empleado' y se tiene el DNI, obtiene la información del empleado actual.
    if (this.role === 'Empleado' && this.dni) {
      this.servicio.getEmpleadoByDni(this.dni).subscribe({
        next: (empleado) => {
          this.empleadoActual = empleado;
          console.log('Empleado cargado por DNI:', this.empleadoActual);
        },
        error: (err) => console.error('Error al obtener el empleado por DNI:', err)
      });
    }
    console.log('Lista de departamentos en Fila3Component (inicial):', this.listaDepartamentos);
  }

  ngOnDestroy(): void {
    // Se desuscribe del observable departamentoSubscription para evitar fugas de memoria.
    if (this.departamentoSubscription) {
      this.departamentoSubscription.unsubscribe();
    }
  }

  trackByDni(index: number, empleado: TipoEmpleado): string {
    return empleado.dni;
  }

  trackById(index: number, departamento: TipoDepartamento): number {
    return departamento.id;
  }

  // Obtiene el nombre del departamento a partir de su ID.
  getDepartamentoName(departamentoId: number): string {
    const departamento = this.departamentoService.listaDepartamentos.find(dep => dep.id === departamentoId);
    return departamento ? departamento.nombre : 'Desconocido';
  }
}
