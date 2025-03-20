// session-detail.component.ts
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { SessionService } from '../../../services/session.service';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-session-detail',
  templateUrl: './session-detail.component.html',
  styleUrls: ['./session-detail.component.scss']
})
export class SessionDetailComponent implements OnInit {
  session: any;
  loading = false;
  error = '';

  constructor(
    private route: ActivatedRoute,
    private sessionService: SessionService,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    const idParam = this.route.snapshot.paramMap.get('id');
    if (idParam) {
      const id = +idParam;
      this.loading = true;
      this.sessionService.getSessionById(id).subscribe({
        next: (data: any) => {
          this.session = data;
          this.loading = false;
        },
        error: (err: any) => {
          console.error('Erreur lors du chargement de la séance', err);
          this.error = 'Erreur lors du chargement de la séance';
          this.loading = false;
        }
      });
    } else {
      this.error = 'ID de séance non fourni.';
    }
  }

  // Vérifie si l'utilisateur connecté est déjà inscrit à la séance
  isUserRegistered(): boolean {
    const currentUser = this.authService.currentAuthUserValue;
    if (!currentUser || !currentUser.id || !this.session || !this.session.sportifs) {
      return false;
    }
    return this.session.sportifs.some((s: any) => s.id === currentUser.id);
  }

  // Méthode pour s'inscrire à la séance
  inscrire(): void {
    const currentUser = this.authService.currentAuthUserValue;
    if (!currentUser || !currentUser.id) {
      // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
      this.router.navigate(['/login']);
      return;
    }
    this.sessionService.inscrireSportif(this.session.id).subscribe({
      next: (res: any) => {
        alert(res.message || 'Inscription réussie');
        // Ajoute le sportif à la liste pour mettre à jour l'affichage
        if (!this.session.sportifs) {
          this.session.sportifs = [];
        }
        this.session.sportifs.push({ id: currentUser.id });
      },
      error: (err: any) => {
        console.error("Erreur lors de l'inscription :", err);
        alert(err.error.message || 'Erreur lors de l\'inscription');
      }
    });
  }
}
