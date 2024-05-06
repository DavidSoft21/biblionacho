import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { TokenService } from '../services/tokens/token.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(private tokenservices: TokenService) {}

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    if (this.tokenservices.isAuthenticated()) {
      request = request.clone({
        setHeaders: {
          Authorization: `Bearer ${this.tokenservices.getToken()}`
        }
      });
    }
    return next.handle(request);
  }
}
