import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormGroup, FormBuilder } from '@angular/forms';
import { BookService } from 'src/app/services/books/book.service';
import { Book } from 'src/app/models/book.model';
import * as $ from 'jquery';

@Component({
  selector: 'app-edit-book',
  templateUrl: './edit-book.component.html',
  styleUrls: ['./edit-book.component.css']
})
export class EditBookComponent implements OnInit {

  id:any;
  updateForm: FormGroup;
  book: any = {};
  data: any;
  errors: any;
  showModalCreate: any;

  constructor(
    private bookService: BookService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private fb: FormBuilder
  ) {
    this.id = activatedRoute.snapshot.paramMap.get('id');
    this.updateForm = this.fb.group({
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
    this.bookService.show(this.id).subscribe(
      (response) => {

        this.data = response.book;
        this.book = this.data;

        // this.updateForm.setValue({
        //   isbn: this.book.isbn,
        //   title: this.book.title,
        //   author: this.book.author,
        //   editorial : this.book.editorial,
        //   edition :this.book.edition,
        //   year : this.book.year,
        //   language :this.book.language,
        //   pages :this.book.pages
        // });

        this.updateForm = this.fb.group({
          isbn:  [this.book.isbn || ''],
          title:  [this.book.title || ''],
          author:  [this.book.author || ''],
          editorial : [this.book.editorial || ''],
          edition : [this.book.edition || ''],
          year :  [this.book.year || ''],
          language : [this.book.language || ''],
          pages:  [this.book.pages || '']
        });
        
    
      },
      (error) => {
        this.errors = error.error;
      }
    );
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

  updateBook() {
    this.cleanError();
    this.bookService.update(this.id, this.updateForm.value).subscribe(
      response => this.handleResponse(response),
      error => this.handleErrors(error)
    );
  }
}