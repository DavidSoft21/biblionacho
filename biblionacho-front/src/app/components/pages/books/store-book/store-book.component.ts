


import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormGroup, FormBuilder } from '@angular/forms';
import { BookService } from 'src/app/services/books/book.service';
import { Book } from 'src/app/models/book.model';
import * as $ from 'jquery';

@Component({
  selector: 'app-store-book',
  templateUrl: './store-book.component.html',
  styleUrls: ['./store-book.component.css']
})
export class StoreBookComponent implements OnInit {

  id:any;
  createForm: FormGroup;
  book: any = {};
  data: any;
  errors: any;
  

  constructor(
    private bookService: BookService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private fb: FormBuilder
  ) {
  
    this.createForm = this.fb.group({
      isbn : [''],
      title: [''],
      author: [''],
      editorial : [''],
      edition :[''],
      year : [''],
      language :[''],
      pages :['']
    });
  }

  ngOnInit(): void {
    
  }

  private handleResponse(response: any): void {
    alert(response.message)
    this.router.navigateByUrl('/book');
  }

  private handleErrors(errors: any): void {
    alert('Unauthorizated or Was Ocurred An Error Internal');
    this.errors = errors.error.errors;
  }

  private cleanError(): void {
    this.errors = null;
  }

  createBook() {
    this.cleanError();
    this.bookService.store(this.createForm.value).subscribe(
      response => this.handleResponse(response),
      error => this.handleErrors(error)
    );
  }
}