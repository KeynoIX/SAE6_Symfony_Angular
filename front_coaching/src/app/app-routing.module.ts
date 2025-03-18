import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

// Import des composants publics
import { HomeComponent } from './components/public/home/home.component';
import { AboutComponent } from './components/public/about/about.component';
import { ContactComponent } from './components/public/contact/contact.component';

// Import des composants pour les coachs
import { CoachListComponent } from './components/coach/coach-list/coach-list.component';
import { CoachDetailComponent } from './components/coach/coach-detail/coach-detail.component';

// Import des composants pour les séances
import { SessionListComponent } from './components/sessions/session-list/session-list.component';
import { SessionDetailComponent } from './components/sessions/session-detail/session-detail.component';

// Import des composants d'authentification
import { LoginComponent } from './components/auth/login/login.component';
import { RegisterComponent } from './components/auth/register/register.component';
import { ForgotPasswordComponent } from './components/auth/forgot-password/forgot-password.component';

// Import des composants pour l'espace utilisateur (dashboard)
import { MonEspaceComponent } from './components/user/mon-espace/mon-espace.component';
import { MyPlanningComponent } from './components/user/mon-espace/my-planning/my-planning.component';
import { MonBilanComponent } from './components/user/mon-espace/mon-bilan/mon-bilan.component';
import { MesReservationsComponent } from './components/user/mon-espace/mes-reservations/mes-reservations.component';
import { ProfileComponent } from './components/user/mon-espace/profile/profile.component';

// Import du composant NotFound (page 404)
import { NotFoundComponent } from './components/not-found/not-found.component';
import { SportifGuard } from './guards/sportif.guard';

const routes: Routes = [
  // Routes publiques
  { path: '', component: HomeComponent },
  { path: 'about', component: AboutComponent },
  { path: 'contact', component: ContactComponent },
  
  // Routes pour la section Coachs
  { path: 'coachs', component: CoachListComponent },
  { path: 'coach/:id', component: CoachDetailComponent },
  
  // Routes pour la section Séances
  { path: 'seances', component: SessionListComponent },
  { path: 'seance/:id', component: SessionDetailComponent },
  
  // Routes d'authentification
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'forgot-password', component: ForgotPasswordComponent },
  
  { 
    path: 'mon-espace/my-planning', 
    component: MyPlanningComponent, 
    canActivate: [SportifGuard] 
  },

  
  // Routes pour l'espace utilisateur (dashboard)
  { path: 'mon-espace', component: MonEspaceComponent,
    children: [
      { path: 'my-planning', component: MyPlanningComponent },
      { path: 'mon-bilan', component: MonBilanComponent },
      { path: 'mes-reservations', component: MesReservationsComponent },
      { path: 'profile', component: ProfileComponent },
      // Redirige l'URL '/mon-espace' vers '/mon-espace/my-planning'
      { path: '', redirectTo: 'my-planning', pathMatch: 'full' }
    ]
  },
  
  // Page 404
  { path: '**', component: NotFoundComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
