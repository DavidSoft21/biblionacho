import { Component } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth/auth.service';
import { TokenService } from 'src/app/services/tokens/token.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent {

  registerForm: FormGroup;
  errors: any;


  constructor(
    private authService: AuthService,
    private router: Router,
    private fb: FormBuilder,
    private tokenservice: TokenService
  ) {
    this.registerForm = this.fb.group({
      first_name: [''],
      last_name: [''],
      email: [''],
      password: [''],
      password_confirmation: ['']
    });


  } 
  
  logout(): void {
    this.authService.logout().subscribe(
      response => this.handleResponse(response),
      errors => this.handleErrors(errors),
    )
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
