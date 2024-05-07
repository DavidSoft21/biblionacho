import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AppRoutingModule } from './app-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { AppComponent } from './app.component';
import { SignUpComponent } from './components/auth/sign-up/sign-up.component';
import { LoginComponent } from './components/auth/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { AuthInterceptor } from './interceptors/auth.interceptor';
import { PageNotFoundComponent } from './components/pages/page-not-found/page-not-found.component';
import { UserComponent } from './components/pages/users/user/user.component';
import { BookComponent } from './components/pages/books/list-book/book.component';
import { LendBookComponent } from './components/pages/lend_books/lend-book/lend-book.component';
import { MenuComponent } from './components/menu/menu.component';
import { HeaderComponent } from './components/shared/header/header.component';
import { FooterComponent } from './components/shared/footer/footer.component';
import { EditBookComponent } from './components/pages/books/edit-book/edit-book.component';
import { StoreBookComponent } from './components/pages/books/store-book/store-book.component';
import { UserListComponent } from './components/pages/users/user-list/user-list.component';
import { UserStoreComponent } from './components/pages/users/user-store/user-store.component';
import { UserEditComponent } from './components/pages/users/user-edit/user-edit.component';
import { LendbookEditComponent } from './components/pages/lend_books/lendbook-edit/lendbook-edit.component';
import { LendbookStoreComponent } from './components/pages/lend_books/lendbook-store/lendbook-store.component';
import { LendbookShowComponent } from './components/pages/lend_books/lendbook-show/lendbook-show.component';


@NgModule({
  declarations: [
    AppComponent,
    SignUpComponent,
    LoginComponent,
    DashboardComponent,
    PageNotFoundComponent,
    UserComponent,
    BookComponent,
    LendBookComponent,
    MenuComponent,
    HeaderComponent,
    FooterComponent,
    EditBookComponent,
    StoreBookComponent,
    UserListComponent,
    UserStoreComponent,
    UserEditComponent,
    LendbookEditComponent,
    LendbookStoreComponent,
    LendbookShowComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true}
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
