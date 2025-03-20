import { Component, OnInit } from '@angular/core';
import { CalendarEvent } from 'angular-calendar';
import { SessionService } from '../../../../services/session.service';
import { AuthService } from '../../../../services/auth.service';

@Component({
  selector: 'app-my-planning',
  templateUrl: './my-planning.component.html',
  styleUrls: ['./my-planning.component.scss']
})
export class MyPlanningComponent implements OnInit {
  viewDate: Date = new Date();
  events: CalendarEvent<{ sessionId: number; theme_seance: string; coachName: string }>[] = [];
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
        // Filtrer uniquement les séances où l'utilisateur (par id) est inscrit
        const sessionsRegistered = data.filter((session: any) => {
          if (session.sportifs && Array.isArray(session.sportifs)) {
            return session.sportifs.some((s: any) => s.id === currentUser.id);
          }
          return false;
        });
        // Transformer les séances filtrées en événements pour le calendrier
        this.events = sessionsRegistered.map((session: any) => {
          return {
            start: new Date(session.date_heure),
            title: session.theme_seance,
            meta: {
              sessionId: session.id,
              theme_seance: session.theme_seance,
              coachName: session.coachId ? `${session.coachId.prenom} ${session.coachId.nom}` : ''
            }
          } as CalendarEvent<{ sessionId: number; theme_seance: string; coachName: string }>;
        });
        this.loading = false;
      },
      error: (err: any) => {
        console.error('Erreur lors du chargement des séances', err);
        this.error = 'Erreur lors du chargement des sessions';
        this.loading = false;
      }
    });
  }
}
