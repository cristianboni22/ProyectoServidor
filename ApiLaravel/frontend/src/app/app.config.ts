import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { provideRouter } from '@angular/router';

import { EmpleadoService } from './servicios/empleado.service';
import { DepartamentosService } from './servicios/departamentos.service'; 
import { AuthService } from './servicios/auth.service';
import { tokeninterceptorserviceInterceptor } from './interceptores/tokeninterceptorservice.interceptor';
import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }), 
    EmpleadoService,
    DepartamentosService,
    provideHttpClient(withInterceptors([tokeninterceptorserviceInterceptor])),
    provideRouter(routes),
    AuthService
  ]
};
