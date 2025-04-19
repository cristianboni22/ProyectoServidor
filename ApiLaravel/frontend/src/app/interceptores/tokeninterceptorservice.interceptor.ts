import { HttpEvent, HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { Observable } from 'rxjs';
import { AuthService } from '../servicios/auth.service';

export const tokeninterceptorserviceInterceptor: HttpInterceptorFn = (req, next): Observable<HttpEvent<any>> => {
  const authService = inject(AuthService);
  const token = authService.getToken();
  let tokenizeReq = req.clone({
    setHeaders: { Authorization: `Bearer ${token}`}
  });
  return next(tokenizeReq);
};
