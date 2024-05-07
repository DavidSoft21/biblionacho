import { Component } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth/auth.service';
import { TokenService } from 'src/app/services/tokens/token.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {

  loginForm: FormGroup;
  errors: any;


  constructor(
    private authService: AuthService,
    private router: Router,
    private fb: FormBuilder,
    private tokenservice: TokenService
  ) {
    this.loginForm = this.fb.group({
      email: [''],
      password: ['']
    });


   } 

  ngOnInit(): void {
   
  }

  onSubmit(): void {
    this.cleanError();
    this.authService.login(this.loginForm.value).subscribe(
      response => this.handleResponse(response),
      errors => this.handleErrors(errors)
    );
  }

  private handleResponse(response: any): void {
    this.tokenservice.handleToken(response.token);
    this.tokenservice.handleUser(response.user);
    this.router.navigateByUrl('/dashboard');
  }

  private handleErrors(errors: any): void {
    this.errors = errors.error.message;
  }

  private cleanError(): void {
    this.errors = null;
  }

}
