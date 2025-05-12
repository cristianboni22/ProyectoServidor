import { Component } from '@angular/core'; // Importa el decorador Component.
import { Fila1Component } from './fila1/fila1.component'; // Importa el componente Fila1Component.
// import { Fila2Component } from './fila2/fila2.component';  // Comentado: Importación del componente Fila2Component.
// import { Fila3Component } from './fila3/fila3.component';  // Comentado: Importación del componente Fila3Component.
import { RouterOutlet } from '@angular/router'; // Importa el componente RouterOutlet.

@Component({
  selector: 'app-root', // Define el selector CSS para este componente.
  standalone: true, // Indica que este componente es un componente standalone.
  imports: [RouterOutlet, Fila1Component], // Declara los módulos/componentes que este componente utiliza.  RouterOutlet se utiliza para renderizar la ruta actual, y Fila1Component es un componente personalizado.
  templateUrl: './app.component.html', // Especifica la ruta al archivo de la plantilla HTML del componente.
  styleUrl: './app.component.css' // Especifica la ruta al archivo de estilos CSS del componente.
})
export class AppComponent {}
// Declara la clase del componente AppComponent.  No contiene ninguna lógica específica en este caso, pero es donde se definirían las propiedades y métodos del componente.
