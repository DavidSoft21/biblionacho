import { Component } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { LendBook } from 'src/app/models/lendbook.model';
import { LendBookService } from 'src/app/services/lend-books/lend-book.service';

@Component({
  selector: 'app-lendbook-store',
  templateUrl: './lendbook-store.component.html',
  styleUrls: ['./lendbook-store.component.css']
})
export class LendbookStoreComponent {

  id:any;
  createForm: FormGroup;
  lendbook: any = {};
  data: any;
  errors: any;
  

  constructor(
    private lendbookService: LendBookService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private fb: FormBuilder
  ) {
  
    this.createForm = this.fb.group({
      isbn : [''],
      returned: [''],
      observations: [''],
      user_id : [''],
      book_id :[''],
      deadline: [''],
      identification: [''],

    });
  }

  ngOnInit(): void {
    
  }

  private handleResponse(response: any): void {
    alert(response.message)
    this.router.navigateByUrl('/lend-book');
  }

  private handleErrors(errors: any): void {
    alert(errors.error.message)
    this.errors = errors.error.errors;
  }

  private cleanError(): void {
    this.errors = null;
  }

  createLendBook() {
    this.cleanError();
    this.lendbookService.store(this.createForm.value).subscribe(
      response => this.handleResponse(response),
      error => this.handleErrors(error)
    );
  }
}
