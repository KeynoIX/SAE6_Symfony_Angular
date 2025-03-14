import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

// AppRouting et composant racine
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

// Modules Angular
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

// Composants Layout
import { NavbarComponent } from './components/layout/navbar/navbar.component';
import { FooterComponent } from './components/layout/footer/footer.component';
import { SidebarComponent } from './components/layout/sidebar/sidebar.component';

// Pages publiques
import { HomeComponent } from './components/public/home/home.component';
import { AboutComponent } from './components/public/about/about.component';
import { ContactComponent } from './components/public/contact/contact.component';

// Composants Coach
import { CoachListComponent } from './components/coach/coach-list/coach-list.component';
import { CoachDetailComponent } from './components/coach/coach-detail/coach-detail.component';

// Composants Séances
import { SessionListComponent } from './components/sessions/session-list/session-list.component';
import { SessionDetailComponent } from './components/sessions/session-detail/session-detail.component';

// Composants Authentification
import { LoginComponent } from './components/auth/login/login.component';
import { RegisterComponent } from './components/auth/register/register.component';
import { ForgotPasswordComponent } from './components/auth/forgot-password/forgot-password.component';

// Composants Espace Utilisateur (Dashboard)
import { MonEspaceComponent } from './components/user/mon-espace/mon-espace.component';
import { MyPlanningComponent } from './components/user/mon-espace/my-planning/my-planning.component';
import { MesReservationsComponent } from './components/user/mon-espace/mes-reservations/mes-reservations.component';
import { MonBilanComponent } from './components/user/mon-espace/mon-bilan/mon-bilan.component';
import { ProfileComponent } from './components/user/mon-espace/profile/profile.component';

// Composants Partagés
import { CardComponent } from './components/shared/card/card.component';
import { ModalComponent } from './components/shared/modal/modal.component';
import { SpinnerComponent } from './components/shared/spinner/spinner.component';
import { ChartComponent } from './components/shared/chart/chart.component';
import { ExerciseCardComponent } from './components/shared/exercise-card/exercise-card.component';
import { NotificationComponent } from './components/shared/notification/notification.component';

// Composant Page 404
import { NotFoundComponent } from './components/not-found/not-found.component';

import { ReactiveFormsModule } from '@angular/forms';
import { AuthInterceptor } from './services/auth.interceptor';


@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    FooterComponent,
    SidebarComponent,
    HomeComponent,
    AboutComponent,
    ContactComponent,
    CoachListComponent,
    CoachDetailComponent,
    SessionListComponent,
    SessionDetailComponent,
    LoginComponent,
    RegisterComponent,
    ForgotPasswordComponent,
    MonEspaceComponent,
    MyPlanningComponent,
    MesReservationsComponent,
    MonBilanComponent,
    ProfileComponent,
    CardComponent,
    ModalComponent,
    SpinnerComponent,
    ChartComponent,
    ExerciseCardComponent,
    NotificationComponent,
    NotFoundComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule  // Ajoutez cette ligne
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
