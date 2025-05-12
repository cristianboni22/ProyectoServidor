import { Injectable } from '@angular/core';  // Importa el decorador Injectable.
import { HttpClient } from '@angular/common/http';  // Importa HttpClient para realizar peticiones HTTP.
import { Router } from '@angular/router';  // Importa Router para la navegación entre rutas.

@Injectable({
  providedIn: 'root'  // Especifica que el servicio está disponible a nivel de toda la aplicación.
})
export class AuthService {
  private URL = 'http://localhost:8000/api';  // Define la URL base de la API.
  constructor(private http: HttpClient, private router: Router) { }  // Constructor que inyecta HttpClient y Router.

  signInUser(user: any) {
    // Envía una petición POST a la API para iniciar sesión.
    return this.http.post<any>(this.URL + '/login', user);
  }

  logout() {
    // Elimina el token de sesión y redirige al usuario a la página de inicio de sesión.
    sessionStorage.removeItem('token');
    this.router.navigate(['/login']);
  }

  loggedIn() {
    // Comprueba si el usuario ha iniciado sesión verificando la existencia del token en sessionStorage.
    return !!sessionStorage.getItem('token');
  }

  getToken() {
    // Obtiene el token de sesión del sessionStorage.
    return sessionStorage.getItem('token');
  }

  signUpUser(user: any) {
    // Envía una petición POST a la API para registrar un nuevo usuario.
    return this.http.post<any>(this.URL + '/register', user);
  }
}