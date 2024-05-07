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
  menuItems = ['User', 'Book', 'Lend-Book'];
  errors: any;
  user: any;

  constructor(
    private authService: AuthService,
    private router: Router,
    private tokenservice: TokenService
  ) { }

  logout(): void {
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