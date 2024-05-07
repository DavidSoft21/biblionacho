import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Book } from 'src/app/models/book.model';
import { environment } from 'src/environments/environment.development'; 

@Injectable({
  providedIn: 'root'
})
export class BookService {

  private readonly API_URL =  environment.api_url;

  constructor(private http: HttpClient) { }

  store(book: Book ): Observable<any> {
    return this.http.post(`${this.API_URL}/books/store`, book);
  };

  index() {
    return this.http.get(`${this.API_URL}/books/index`);
  }

  delete(id:any) {

    return this.http.delete(`${this.API_URL}/books/destroy/`+id);

  }

  update(id: any,book:any) {

    return this.http.put(`${this.API_URL}/books/update/${id}`, book);

  }

  show(id: any): Observable<any> {
    return this.http.get(`${this.API_URL}/books/show/${id}`);
  };


}