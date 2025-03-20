// mes-reservations.component.ts
import { Component, OnInit } from '@angular/core';
import { SessionService } from '../../../../services/session.service';
import { AuthService } from '../../../../services/auth.service';

@Component({
  selector: 'app-mes-reservations',
  templateUrl: './mes-reservations.component.html',
  styleUrls: ['./mes-reservations.component.scss']
})
export class MesReservationsComponent implements OnInit {
  reservations: any[] = [];
  loading: boolean = false;
  error: string = '';

  constructor(
    private sessionService: SessionService,
    private authService: AuthService
  ) {}

  ngOnInit(): void {
    this.loading = true;
    this.sessionService.getSessions().subscribe({
      next: (data: any[]) => {
        const currentUser = this.authService.currentAuthUserValue;
        if (!currentUser || !currentUser.id) {
          this.error = 'Utilisateur non connecté.';
          this.loading = false;
          return;
        }
        // Filtrer les séances où le sportif (par id) est inscrit
        this.reservations = data.filter((session: any) => {
          if (session.sportifs && Array.isArray(session.sportifs)) {
            return session.sportifs.some((s: any) => s.id === currentUser.id);
          }
          return false;
        });
        this.loading = false;
      },
      error: (err: any) => {
        console.error('Erreur lors du chargement des séances', err);
        this.error = 'Erreur lors du chargement de vos réservations';
        this.loading = false;
      }
    });
  }

  // Méthode pour annuler la réservation d'une séance
  cancelReservation(session: any): void {
    if (confirm("Voulez-vous vraiment annuler votre réservation pour cette séance ?")) {
      this.sessionService.cancelReservation(session.id).subscribe({
        next: (res: any) => {
          alert(res.message || 'Désinscription réussie');
          // Met à jour la liste en retirant la séance désinscrite
          this.reservations = this.reservations.filter(s => s.id !== session.id);
        },
        error: (err: any) => {
          console.error("Erreur lors de la désinscription :", err);
          alert(err.error.message || 'Erreur lors de l\'annulation de la réservation');
        }
      });
    }
  }
}
