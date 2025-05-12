import { HttpEvent, HttpInterceptorFn } from '@angular/common/http';  // Importa HttpEvent e HttpInterceptorFn.
import { inject } from '@angular/core';  // Importa la función inject.
import { Observable } from 'rxjs';  // Importa Observable.
import { AuthService } from '../servicios/auth.service';  // Importa el servicio AuthService.

export const tokeninterceptorserviceInterceptor: HttpInterceptorFn = (req, next): Observable<HttpEvent<any>> => {
  // Define un interceptor HTTP funcional llamado tokeninterceptorserviceInterceptor.
  const authService = inject(AuthService);  // Inyecta el servicio AuthService.  Esto es necesario en interceptores funcionales.
  const token = authService.getToken();  // Obtiene el token de autenticación del servicio AuthService.

  // Clona la petición original y añade la cabecera de Autorización con el token.
  let tokenizeReq = req.clone({
    setHeaders: { Authorization: `Bearer ${token}` }  // Añade la cabecera 'Authorization' con el valor 'Bearer [token]'.
  });

  // Pasa la petición modificada al siguiente manejador en la cadena de interceptores.
  return next(tokenizeReq);
};
