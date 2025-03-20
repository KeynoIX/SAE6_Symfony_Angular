import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  model: any = {};
  flashMessage: string = '';
  flashMessageType: 'success' | 'danger' | 'warning' = 'danger';

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onSubmit() {
    this.authService.login(this.model.email, this.model.password).subscribe({
      next: () => {
        this.flashMessage = 'Connexion réussie !';
        this.flashMessageType = 'success';
        setTimeout(() => this.router.navigate(['/']), 500);
      },
      error: err => {
        console.error('Erreur lors de la connexion', err);
        this.flashMessage = 'Erreur lors de la connexion. Vérifiez vos identifiants.';
        this.flashMessageType = 'danger';
      }
    });
  }
}
