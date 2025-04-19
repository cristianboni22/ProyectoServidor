import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { EmpleadoService } from '../../servicios/empleado.service';

@Component({
  selector: 'app-fila2',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './fila2.component.html',
  styleUrl: './fila2.component.css'
})
export class Fila2Component {
  constructor(public servicio: EmpleadoService){}
}
