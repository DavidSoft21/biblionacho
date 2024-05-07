import { Component } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';
import { Book } from 'src/app/models/book.model';
import { BookService } from 'src/app/services/books/book.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-book',
  templateUrl: './book.component.html',
  styleUrls: ['./book.component.css']
})
export class BookComponent {

 
  books: any;
  errors: any;

  constructor(
    private bookService: BookService,
    private router: Router,
    private fb: FormBuilder,
    private modalService: NgbModal
  ) {


  } 

  ngOnInit(): void {
    this.bookService.index().subscribe(
      response => { this.books = response; }, 
      errors => this.handleErrors(errors),
    );
  }

  deleteBook(id: any, iControl: any): void {
    
    let userResponse = confirm("¿Desea eliminar libro?");
    if (userResponse) {
      this.cleanError();
      console.log(id);
      
      this.bookService.delete(id).subscribe(
        response => this.handleResponse(response),
        errors => this.handleErrors(errors),
      );
    } else {
      this.router.navigateByUrl('/book');
    }

  }

  private handleResponse(response: any): void {
    alert(response.message);
    this.router.navigateByUrl('/dashboard');
  }

  private handleErrors(errors: any): void {
    alert('Error Internal Server');
    this.errors = errors.error.errors;
    this.router.navigateByUrl('/book');
  }

  private cleanError(): void {
    this.errors = null;
  }

}