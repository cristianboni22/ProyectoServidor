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
  user: TipoEmpleado = { login: "", password: "", dni: "", nombre_completo: "", departamento_id: 0 };
  errorMessage: string = '';
  constructor(
    private authService: AuthService,
    private router: Router
  ) { }

  signIn() {
    if (this.user.login && this.user.password) {
      this.authService.signInUser(this.user)
        .subscribe(
          (res: any) => {
  
            if (res.mensaje !== "Usuario y/o contraseña incorrectos") {
              console.log('Respuesta del backend:', res); // <- Esto te ayudará a ver el rol real que devuelve el backend
            
              sessionStorage.setItem('token', res.mensaje);
              sessionStorage.setItem('login', this.user.login);
              
              const loginLimpio = this.user.login.trim().toLowerCase();
              const adminLogin = 'superadmin'; // Cambia esto si tu login de superadmin es diferente
            
              // Verifica que el rol recibido es el correcto
              if (loginLimpio === adminLogin || res.role === 'SuperAdmin') {
                sessionStorage.setItem('role', 'SuperAdmin');
              } else {
                sessionStorage.setItem('role', 'Empleado');
              }
            
              this.router.navigate(['/editar']);
            }},
            (error) => {
              // Capturar mensaje personalizado del backend
              if (error.status === 404 || error.status === 401) {
                // Espera que el backend devuelva { error: "mensaje" }
                this.errorMessage = error.error?.error || 'Error desconocido al iniciar sesión.';
              } else {
                this.errorMessage = 'Error del servidor. Intente más tarde.';
              }
              console.error('Error al iniciar sesión:', this.errorMessage);
            }
          );
        } else {
          this.errorMessage = 'Por favor, complete todos los campos.';
        }
      }
}
