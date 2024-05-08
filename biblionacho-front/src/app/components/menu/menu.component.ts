// menu.component.ts
import { Component } from '@angular/core';
import { AuthService } from 'src/app/services/auth/auth.service';
import { TokenService } from 'src/app/services/tokens/token.service';
import { Router } from '@angular/router';


@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent {
  menuItems = ['User', 'Book', 'Lend-Book', 'Lendbook-store', 'Lendbook-Show',];
  errors: any;
  user: any;
  show: any;

  constructor(
    private authService: AuthService,
    private router: Router,
    private tokenservice: TokenService
  ) { 
    localStorage.getItem('access_token') ? this.show = true : this.show = false
  }

  logout(): void {
    this.show = !this.show ;
    this.authService.logout().subscribe(
      response => this.handleResponse(response),
      errors => this.handleErrors(errors),
    )
  }

  getUser(): void {
    this.user = this.tokenservice.getUser();
  }

  private handleResponse(response: any): void {
    this.tokenservice.revokeToken();
    this.router.navigateByUrl('/login');
  }

  private handleErrors(errors: any): void {
    this.errors = errors.error.message;
  }

  private cleanError(): void {
    this.errors = null;
  }
}