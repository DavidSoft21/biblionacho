import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { LendBookService } from 'src/app/services/lend-books/lend-book.service';
import { FormGroup, FormBuilder } from '@angular/forms';
import { LendBook } from 'src/app/models/lendbook.model';



@Component({
  selector: 'app-lend-book',
  templateUrl: './lend-book.component.html',
  styleUrls: ['./lend-book.component.css']
})
export class LendBookComponent {

  lendbooks: any;
  errors: any;

  constructor(
    private lendBookService: LendBookService,
    private router: Router,
    private fb: FormBuilder,

  ) {


  } 

  ngOnInit(): void {
    this.lendBookService.index().subscribe(
      response => { this.lendbooks = response; }, 
      errors => this.handleErrors(errors),
    );
  }


  deleteLendBook(id: any, iControl: any): void {
    
    let userResponse = confirm("¿Desea eliminar el registro?");
    if (userResponse) {
      this.cleanError();
      console.log(id);
      
      this.lendBookService.delete(id).subscribe(
        response => this.handleResponse(response),
        errors => this.handleErrors(errors),
      );
    } else {
      this.router.navigateByUrl('/lend-book');
    }

  }

  private handleResponse(response: any): void {
    alert(response.message);
    this.router.navigateByUrl('/dashboard');
  }

  private handleErrors(errors: any): void {
    alert('Error Internal Server');
    this.errors = errors.error.errors;
    this.router.navigateByUrl('/lend-book');
  }

  private cleanError(): void {
    this.errors = null;
  }
}
