import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core'; // Importa ApplicationConfig, que se utiliza para configurar una aplicación Angular, y provideZoneChangeDetection para optimizar la detección de cambios.
import { provideHttpClient, withInterceptors } from '@angular/common/http'; // Importa provideHttpClient para habilitar el cliente HTTP en la aplicación y withInterceptors para configurar interceptores HTTP.
import { provideRouter } from '@angular/router'; // Importa provideRouter para configurar el enrutamiento de la aplicación.

import { EmpleadoService } from './servicios/empleado.service'; // Importa el servicio EmpleadoService.
import { DepartamentosService } from './servicios/departamentos.service'; // Importa el servicio DepartamentosService.
import { AuthService } from './servicios/auth.service'; // Importa el servicio AuthService.
import { tokeninterceptorserviceInterceptor } from './interceptores/tokeninterceptorservice.interceptor'; // Importa el interceptor tokeninterceptorserviceInterceptor.
import { routes } from './app.routes'; // Importa las rutas de la aplicación desde app.routes.ts.

export const appConfig: ApplicationConfig = {
    // Define la configuración de la aplicación Angular.
    providers: [
        provideZoneChangeDetection({ eventCoalescing: true }),  // Configura la detección de cambios de Angular para agrupar eventos y mejorar el rendimiento.
        EmpleadoService, // Registra el servicio EmpleadoService como proveedor, haciéndolo disponible para inyección de dependencias.
        DepartamentosService, // Registra el servicio DepartamentosService como proveedor.
        provideHttpClient(withInterceptors([tokeninterceptorserviceInterceptor])), // Configura el cliente HTTP con el interceptor tokeninterceptorserviceInterceptor, que probablemente añade un token de autenticación a las peticiones.
        provideRouter(routes), // Configura el enrutamiento de la aplicación con las rutas definidas en app.routes.ts.
        AuthService // Registra el servicio AuthService como proveedor.
    ]
};
