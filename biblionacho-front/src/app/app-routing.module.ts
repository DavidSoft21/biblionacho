import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { SignUpComponent } from './components/sign-up/sign-up.component';
import { LoginComponent } from './components/login/login.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found.component';
import { isUserAuthenticatedGuard } from './guards/auth.guard';
import { isGuestGuard } from './guards/auth.guard';

const routes: Routes = [
  { path: 'signup', component: SignUpComponent, canActivate: [isGuestGuard]},
  { path: 'login', component: LoginComponent, canActivate: [isGuestGuard]},
  { path: 'dashboard', component: DashboardComponent, canActivate: [isUserAuthenticatedGuard] },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: '**', component: PageNotFoundComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
