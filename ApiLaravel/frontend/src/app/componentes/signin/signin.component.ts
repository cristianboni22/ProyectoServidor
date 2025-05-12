import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { TipoEmpleado } from '../../modelos/tipoEmpleado';
import { AuthService } from '../../servicios/auth.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-signin',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './signin.component.html',
  styleUrls: ['./signin.component.css']
})
export class SigninComponent {
  user: TipoEmpleado = { login: "", password: "", dni: "", nombre_completo: "", departamento_id: 0 };  // Objeto para almacenar los datos del usuario que intenta iniciar sesión.
  errorMessage: string = ''; // Almacena el mensaje de error en caso de fallo de inicio de sesión.

  constructor(
    private authService: AuthService,  // Servicio para la autenticación de usuarios.
    private router: Router           // Servicio para la navegación entre rutas de la aplicación.
  ) { }

  signIn() {
    // Función que se llama al intentar iniciar sesión.
    if (this.user.login && this.user.password) {
      // Verifica que se hayan ingresado el login y la contraseña.
      this.authService.signInUser(this.user)
        .subscribe(
          (res: any) => {
            // Si el inicio de sesión es exitoso, el servicio authService devuelve una respuesta.

            if (res.mensaje !== "Usuario y/o contraseña incorrectos") {
              // Verifica que la respuesta no sea un mensaje de error de credenciales incorrectas.
              console.log('Respuesta del backend:', res);
              // Imprime la respuesta del backend en la consola (útil para depuración).

              sessionStorage.setItem('token', res.mensaje);  // Guarda el token de autenticación en el almacenamiento de sesión.
              //sessionStorage.setItem('login', this.user.login);  // Guarda el login del usuario en el almacenamiento de sesión.
              sessionStorage.setItem('dni', res.dni);    // Guarda el DNI del usuario en el almacenamiento de sesión.

              // Determina el rol del usuario y lo guarda en el almacenamiento de sesión.
              if (res.role === 'SuperAdmin') {
                sessionStorage.setItem('role', 'SuperAdmin');
              } else {
                sessionStorage.setItem('role', 'Empleado');
              }

              this.router.navigate(['/editar']);  // Redirige al usuario a la página '/editar'.
            }
          },
          (error) => {
            // Si el inicio de sesión falla, se maneja el error.
            if (error.status === 404 || error.status === 401) {
              // Si el error es 404 o 401, se muestra un mensaje de error de credenciales incorrectas.
              this.errorMessage = error.error?.error || 'Usuario o contraseña incorrectos.';
            } else if (error.status === 422 && error.error?.error) {
              // Si el error es 422, se muestra un mensaje de error de validación de los datos.
              const errores = error.error.error;
              this.errorMessage = Object.keys(errores)
                .map(campo => `${campo}: ${errores[campo]}`)
                .join('\n');
            } else {
              // Para otros errores, se muestra un mensaje de error genérico del servidor.
              this.errorMessage = 'Error del servidor. Intente más tarde.';
            }
            console.error('Error al iniciar sesión:', this.errorMessage);
            // Imprime el mensaje de error en la consola.
          }
        );
    } else {
      this.errorMessage = 'Por favor, complete todos los campos.';
      // Si no se ingresa login o contraseña, se muestra un mensaje de error.
    }
  }
}