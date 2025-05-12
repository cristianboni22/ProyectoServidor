import { Routes } from '@angular/router'; // Importa la interfaz Routes del módulo @angular/router.
import { Fila3Component } from './componentes/fila3/fila3.component'; // Importa el componente Fila3Component.
import { SigninComponent } from './componentes/signin/signin.component'; // Importa el componente SigninComponent.
import { SignupComponent } from './componentes/signup/signup.component'; // Importa el componente SignupComponent.
import { authGuard } from './guardianes/auth.guard'; // Importa el guardián authGuard.

export const routes: Routes = [
    // Define un array de objetos Route, donde cada objeto representa una ruta de la aplicación.
    {
        path: 'editar', // Define la ruta '/editar'.
        component: Fila3Component, // Especifica que el componente Fila3Component se renderizará cuando se acceda a esta ruta.
        canActivate: [authGuard] // Aplica el guardián authGuard a esta ruta.
    },
    {
        path: '', // Define la ruta por defecto (raíz).
        redirectTo: '/editar', // Redirige a la ruta '/editar'.
        pathMatch: 'full' // Especifica que la redirección se aplica solo cuando la ruta coincide completamente con ''.
    },
    {
        path: 'login', // Define la ruta '/login'.
        component: SigninComponent // Especifica que el componente SigninComponent se renderizará cuando se acceda a esta ruta (página de inicio de sesión).
    },
    {
        path: 'registro', // Define la ruta '/registro'.
        component: SignupComponent // Especifica que el componente SignupComponent se renderizará cuando se acceda a esta ruta (página de registro).
    }
];
