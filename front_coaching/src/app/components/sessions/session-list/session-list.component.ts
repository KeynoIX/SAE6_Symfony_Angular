import { Component, OnInit } from '@angular/core';
import { SessionService } from '../../../services/session.service';

@Component({
  selector: 'app-session-list',
  templateUrl: './session-list.component.html',
  styleUrls: ['./session-list.component.scss']
})
export class SessionListComponent implements OnInit {
  sessions: any[] = [];
  loading = false;
  error = '';

  constructor(private sessionService: SessionService) {}

  ngOnInit(): void {
    this.loading = true;
    this.sessionService.getSessions().subscribe({
      next: (data: any) => {
        this.sessions = data;
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
