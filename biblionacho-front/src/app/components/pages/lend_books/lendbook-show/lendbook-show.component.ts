import { Component } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormGroup, FormBuilder } from '@angular/forms';
import { LendBookService } from 'src/app/services/lend-books/lend-book.service';
import { LendBook } from '../../../../models/lendbook.model';

@Component({
  selector: 'app-lendbook-show',
  templateUrl: './lendbook-show.component.html',
  styleUrls: ['./lendbook-show.component.css']
})
export class LendbookShowComponent {

  id:any;
  showForm: FormGroup;
  lendBook: any = {};
  data: any;
  errors: any;
  

  constructor(
    private lendBookService: LendBookService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private fb: FormBuilder,
  
  ) {
    this.id = activatedRoute.snapshot.paramMap.get('id');
    this.showForm = this.fb.group({
      id : [''],
    });
  }

  ngOnInit(): void {
    
  }

  private handleResponse(response: any): void {
    this.data = response.response;
  }

  private handleErrors(errors: any): void {
    this.data = null;
    this.errors = errors.error.errors;
  }

  private cleanError(): void {
    this.errors = null;
  }

  showBook() {
    this.cleanError();
    const id = this.showForm.value.id;
    this.lendBookService.show(id).subscribe(
        response => this.handleResponse(response),
        error => this.handleErrors(error)
    );
  }
}