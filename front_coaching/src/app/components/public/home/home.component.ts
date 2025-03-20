import { Component, OnInit } from '@angular/core';
import { CalendarEvent } from 'angular-calendar';
import { SessionService } from '../../../../app/services/session.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  viewDate: Date = new Date();
  events: CalendarEvent<{ sessionId: number; theme_seance: string; coachName: string }>[] = [];
  loading = false;
  error = '';

  constructor(private sessionService: SessionService, private router: Router) {}

  ngOnInit(): void {
    this.loading = true;
    this.sessionService.getSessions().subscribe({
      next: (data: any[]) => {
        // Transformation des séances en événements pour le calendrier
        this.events = data.map((session: any) => {
          return {
            start: new Date(session.date_heure),
            title: session.theme_seance,
            meta: {
              sessionId: session.id,
              theme_seance: session.theme_seance,
              coachName: (session.coachId && session.coachId.prenom) 
                           ? `${session.coachId.prenom} ${session.coachId.nom}` 
                           : ''
            }
          } as CalendarEvent<{ sessionId: number; theme_seance: string; coachName: string }>;
        });
        this.loading = false;
      },
      error: (err: any) => {
        console.error('Erreur lors du chargement des sessions', err);
        this.error = 'Erreur lors du chargement des sessions';
        this.loading = false;
      }
    });
  }

  // Méthode optionnelle pour gérer le clic sur un événement de façon programmatique
  onEventClicked(event: CalendarEvent<{ sessionId: number }>): void {
    if (event && event.meta && event.meta.sessionId) {
      this.router.navigate(['/seance', event.meta.sessionId]);
    }
  }
}
