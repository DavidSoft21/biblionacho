import { Injectable } from '@angular/core';
import { UserRegister } from 'src/app/models/user-register.models';

@Injectable({
  providedIn: 'root'
})
export class TokenService {

  constructor() { }
 
  handleToken(token: string) : void {
    localStorage.setItem('access_token', token);
  }

  handleUser(user: UserRegister): void {
   // console.log(user,'servicestoken');
    
    localStorage.setItem('user', JSON.stringify(user));
  }

  getUser(): UserRegister | null {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }

  getToken() : string | null {
    return localStorage.getItem('access_token');
  }

  revokeToken() : void {
    localStorage.removeItem('access_token');
    localStorage.removeItem('user');
  }

  isAuthenticated(): boolean {
    const token = this.getToken();
    return !!token;
  }
}