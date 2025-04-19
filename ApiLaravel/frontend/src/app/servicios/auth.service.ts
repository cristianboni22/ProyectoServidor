import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private URL = 'http://localhost:8000/api';
  constructor(private http: HttpClient, private router: Router) { }

  signInUser(user:any) {
    return this.http.post<any>(this.URL + '/login', user);
  }
  logout() {
    sessionStorage.removeItem('token');
    this.router.navigate(['/login']);
  }
  loggedIn() {
    return !!sessionStorage.getItem('token');
  }
  getToken() {
    return sessionStorage.getItem('token');
  }
  signUpUser(user:any) {
    return this.http.post<any>(this.URL + '/register', user);
  }
}
