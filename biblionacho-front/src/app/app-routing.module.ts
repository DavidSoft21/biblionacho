import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { SignUpComponent } from './components/auth/sign-up/sign-up.component';
import { LoginComponent } from './components/auth/login/login.component';

import { DashboardComponent } from './components/dashboard/dashboard.component';
import { UserComponent } from './components/pages/users/user/user.component';
import { BookComponent } from './components/pages/books/list-book/book.component';
import { EditBookComponent } from './components/pages/books/edit-book/edit-book.component';
import { StoreBookComponent } from './components/pages/books/store-book/store-book.component';
import { LendBookComponent } from './components/pages/lend_books/lend-book/lend-book.component';
import { PageNotFoundComponent } from './components/pages/page-not-found/page-not-found.component';
import { LendbookStoreComponent } from './components/pages/lend_books/lendbook-store/lendbook-store.component';
import { LendbookEditComponent } from './components/pages/lend_books/lendbook-edit/lendbook-edit.component';
import { LendbookShowComponent } from './components/pages/lend_books/lendbook-show/lendbook-show.component';
import { isUserAuthenticatedGuard } from './guards/auth.guard';
import { isGuestGuard } from './guards/auth.guard';

const routes: Routes = [
  { path: 'signup', component: SignUpComponent, canActivate: [isGuestGuard]},
  { path: 'login', component: LoginComponent, canActivate: [isGuestGuard]},
  { path: 'dashboard', component: DashboardComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'user', component: UserComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'book', component: BookComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'edit-book/:id', component: EditBookComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'store-book', component: StoreBookComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'lend-book', component: LendBookComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'lendbook-store', component: LendbookStoreComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'lendbook-edit/:id', component: LendbookEditComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: 'lendbook-show/:id', component: LendbookShowComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: '**', component: PageNotFoundComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
