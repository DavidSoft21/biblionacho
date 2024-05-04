import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { AuthCredentials } from '../../models/auth-credentials.model';
import { UserRegister } from 'src/app/models/user-register.models';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private readonly  API_URL =  environment.api_url;

  constructor(private http: HttpClient) { }

  login(credentials: AuthCredentials ): Observable<any> {
    return this.http.post(`${this.API_URL}/login`, credentials);
  };

  register(user: UserRegister ): Observable<any> {
    return this.http.post(`${this.API_URL}/register`, user);
  };

  logout(): Observable<any> {
    return this.http.post(`${this.API_URL}/logout`,{});
  };

}
