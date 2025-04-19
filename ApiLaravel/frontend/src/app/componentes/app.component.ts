import { Component } from '@angular/core';
import { Fila1Component } from './fila1/fila1.component';
// import { Fila2Component } from './fila2/fila2.component';
// import { Fila3Component } from './fila3/fila3.component';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, Fila1Component],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {}
