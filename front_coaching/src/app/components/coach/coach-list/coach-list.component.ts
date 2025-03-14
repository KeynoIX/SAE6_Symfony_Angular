import { Component, OnInit } from '@angular/core';
import { CoachService } from '../../../services/coach.service';

@Component({
  selector: 'app-coach-list',
  templateUrl: './coach-list.component.html',
  styleUrls: ['./coach-list.component.scss']
})
export class CoachListComponent implements OnInit {
  coachs: any[] = [];
  loading = false;
  error = '';

  constructor(private coachService: CoachService) {}

  ngOnInit(): void {
    this.loading = true;
    this.coachService.getCoachs().subscribe({
      next: (data: any) => {
        this.coachs = data;
        this.loading = false;
      },
      error: (err: any) => {
        console.error('Erreur lors du chargement des coachs', err);
        this.error = 'Erreur lors du chargement des coachs';
        this.loading = false;
      }
    });
  }
}
