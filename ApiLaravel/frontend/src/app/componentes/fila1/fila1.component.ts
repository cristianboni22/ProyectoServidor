import { Component } from '@angular/core';
import { AuthService } from '../../servicios/auth.service';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-fila1',
  standalone: true,
  imports: [RouterLink,CommonModule],
  templateUrl: './fila1.component.html',
  styleUrl: './fila1.component.css'
})
export class Fila1Component {
  constructor(public authService: AuthService) {}
}
