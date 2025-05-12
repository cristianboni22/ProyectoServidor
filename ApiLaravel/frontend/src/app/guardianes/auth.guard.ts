import { CanActivateFn, Router } from '@angular/router'; // Importa CanActivateFn y Router del módulo @angular/router.
import { AuthService } from '../servicios/auth.service'; // Importa el servicio AuthService.
import { inject } from '@angular/core'; // Importa la función inject.

export const authGuard: CanActivateFn = (route, state) => {
  // Define una función guardián de ruta llamada authGuard que implementa CanActivateFn.
  const authService = inject(AuthService); // Inyecta el servicio AuthService.
  const router = inject(Router); // Inyecta el servicio Router.

  if (authService.loggedIn()) {
    // Llama al método loggedIn() del servicio AuthService para verificar si el usuario ha iniciado sesión.
    return true; // Si el usuario ha iniciado sesión, permite el acceso a la ruta.
  }

  router.navigate(['/login']); // Si el usuario no ha iniciado sesión, lo redirige a la página de inicio de sesión.
  return false; // Impide el acceso a la ruta.
};
