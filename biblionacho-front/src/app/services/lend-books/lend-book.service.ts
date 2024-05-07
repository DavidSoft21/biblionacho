import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { LendBook } from 'src/app/models/lendbook.model';
import { environment } from 'src/environments/environment.development';

@Injectable({
  providedIn: 'root'
})
export class LendBookService {
  
  private readonly API_URL =  environment.api_url;

  constructor(private http: HttpClient) { }

  store(lendbook: LendBook ): Observable<any> {
    return this.http.post(`${this.API_URL}/lendbooks/store`, lendbook);
  };

  index() {
    return this.http.get(`${this.API_URL}/lendbooks/index`);
  }

  delete(id:any) {

    return this.http.delete(`${this.API_URL}/lendbooks/destroy/`+id);

  }

  update(id: any,lendbook:any) {

    return this.http.put(`${this.API_URL}/lendbooks/update/${id}`, lendbook);

  }

  show(id: any): Observable<any> {
    return this.http.get(`${this.API_URL}/lendbooks/show/${id}`);
  };

}
