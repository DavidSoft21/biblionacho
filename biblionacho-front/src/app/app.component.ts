import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'biblionacho-front';

  public isAuth = false;

  public isAuthenticated: boolean | undefined;
  
  constructor() { }

  ngOnInit(
  ) {
  
    if (localStorage.getItem('access_token')) {
      this.isAuth = true
    } else {
      this.isAuth = false
    }
  }

}
