import { Component, OnInit } from '@angular/core';
import { CalendarEvent, CalendarMonthViewDay } from 'angular-calendar';
import { SessionService } from '../../../../services/session.service';

@Component({
  selector: 'app-my-planning',
  templateUrl: './my-planning.component.html',
  styleUrls: ['./my-planning.component.scss']
})
export class MyPlanningComponent implements OnInit {
  viewDate: Date = new Date();
  events: CalendarEvent<{ theme_seance: string; coachName: string }>[] = [];
  loading = false;
  error = '';

  constructor(private sessionService: SessionService) {}

  ngOnInit(): void {
    this.loading = true;
    // Récupérer toutes les sessions
    this.sessionService.getSessions().subscribe({
      next: (data: any[]) => {
        // Transformation en CalendarEvent
        this.events = data.map(session => {
          return {
            start: new Date(session.date_heure),
            title: session.theme_seance, // Titre par défaut (pas forcément affiché si on utilise un template)
            meta: {
              theme_seance: session.theme_seance,
              coachName: session.coachId
                ? `${session.coachId.prenom} ${session.coachId.nom}`
                : 'Inconnu'
            }
          } as CalendarEvent<{ theme_seance: string; coachName: string }>;
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
}
