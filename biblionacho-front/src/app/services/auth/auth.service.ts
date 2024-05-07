import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { AuthCredentials } from 'src/app/models/auth-credentials.model';
import { UserRegister } from 'src/app/models/user-register.models';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private readonly  API_URL =  environment.api_url;

  constructor(private http: HttpClient) { }

  login(credentials: AuthCredentials ): Observable<any> {
    return this.http.post(`${this.API_URL}/auth/login`, credentials);
  };

  register(user: UserRegister): Observable<any> {
    return this.http.post(`${this.API_URL}/auth/register`, user);
  };

  logout(): Observable<any> {
    return this.http.delete(`${this.API_URL}/auth/logout`,{});
  };

}
