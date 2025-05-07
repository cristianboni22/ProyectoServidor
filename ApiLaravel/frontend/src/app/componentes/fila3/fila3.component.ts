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
  login: string = '';
  role: string = '';
  listaDepartamentos: TipoDepartamento[] = [];
  empleadoActual: TipoEmpleado | null = null;
  private departamentoSubscription: Subscription | undefined;

  constructor(
    public servicio: EmpleadoService,
    public departamentoService: DepartamentosService
  ) { }

  ngOnInit(): void {
    // Obtener datos de sesión
    this.login = sessionStorage.getItem('login') || '';
    this.role = (sessionStorage.getItem('role') || 'Empleado').trim();

    console.log('ROL ACTUAL:', this.role);

    // Cargar datos iniciales
    this.servicio.obtenerEmpleados();
    this.departamentoService.obtenerDepartamentos();
    this.listaDepartamentos = this.departamentoService.listaDepartamentos;

    // Suscribirse a cambios en la lista de departamentos
    this.departamentoSubscription = this.departamentoService.actualizacionLista$.subscribe(() => {
      this.listaDepartamentos = this.departamentoService.listaDepartamentos;
      console.log('Lista de departamentos en Fila3Component (actualización):', this.listaDepartamentos);
    });

    // Si es empleado normal, cargar sus datos
    if (this.role === 'Empleado') {
      this.servicio.getEmpleados().subscribe({
        next: (empleados) => {
          this.empleadoActual = empleados.find(emp => emp.login === this.login) || null;
        },
        error: (err) => console.error('Error al buscar empleado actual:', err)
      });
    }
    console.log('Lista de departamentos en Fila3Component (inicial):', this.listaDepartamentos);
  }

  ngOnDestroy(): void {
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

  getDepartamentoName(departamentoId: number): string {
    const departamento = this.departamentoService.listaDepartamentos.find(dep => dep.id === departamentoId);
    return departamento ? departamento.nombre : 'Desconocido';
  }
}
