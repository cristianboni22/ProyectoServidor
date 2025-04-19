import { Component } from '@angular/core';
import { TipoEmpleado } from '../../modelos/tipoEmpleado';
import { AuthService } from '../../servicios/auth.service';
import { Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { EmpleadoService } from '../../servicios/empleado.service';
import { DepartamentosService } from '../../servicios/departamentos.service';
import { TipoDepartamento } from '../../modelos/tipoDepartamento'; 
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-signup',
  standalone: true,
  imports: [FormsModule,CommonModule],
  templateUrl: './signup.component.html',
  styleUrl: './signup.component.css'
})
export class SignupComponent {


  user:TipoEmpleado = {login:"",password:"",dni:"",nombre_completo:"",departamento_id:""};
  listaDepartamentos: TipoDepartamento[] = [];
  constructor(
    private authService: AuthService,
    private router: Router,
    public servicio: EmpleadoService,
    public departamentoService: DepartamentosService
  ) {}

  ngOnInit(): void {
    // Se llama al cargar el componente

    this.servicio.obtenerEmpleados(); // Se cargan los empleados desde el servicio
    this.departamentoService.obtenerDepartamentos(); // Se hace la petición para cargar los departamentos

    // Se obtiene la lista de departamentos del observable
    this.departamentoService.getDepartamentos().subscribe(data => {
      this.listaDepartamentos = data; // Se guarda la lista en la variable del componente
    });
  }
  // Función para seguimiento de departamentos por su ID
  trackById(index: number, departamento: any): string {
    return departamento.id;
  }

  // Función para obtener el nombre de un departamento a partir de su ID
  getDepartamentoName(departamentoId: number): string {
    const departamento = this.listaDepartamentos.find(dep => dep.id === departamentoId);
    return departamento ? departamento.nombre : 'Desconocido';
  }
//Registro iniciando sesion y redireccion 
  //signUp() {
  //  this.authService.signUpUser(this.user)
  //    .subscribe(
  //      (res:any) => {
  //        console.log(res);
  //        sessionStorage.setItem('token', res.mensaje);
  //        this.router.navigate(['/login'])
  //      },
  //      err => console.log(err)
  //    )
  //}

  //Registro sin iniciar sesion solo creacion 
  signUp() {
    this.authService.signUpUser(this.user)
      .subscribe(
        (res: any) => {
          console.log(res);
          this.router.navigate(['/login']);
        },
        err => console.log(err)
      );
  }
}
