import { Routes } from '@angular/router';
import { Fila2Component } from './componentes/fila2/fila2.component';
import { Fila3Component } from './componentes/fila3/fila3.component';
import { SigninComponent } from './componentes/signin/signin.component';
import { SignupComponent } from './componentes/signup/signup.component';
import { authGuard } from './guardianes/auth.guard';

export const routes: Routes = [
    {path:'crear',component:Fila2Component,canActivate:[authGuard]},
    {path:'editar',component:Fila3Component,canActivate:[authGuard]},
    {path:'',redirectTo:'/editar',pathMatch:'full'},
    {path:'login',component: SigninComponent},
    {path:'registro',component: SignupComponent}
];
