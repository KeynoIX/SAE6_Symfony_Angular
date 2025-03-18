import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SessionService } from '../../../services/session.service';

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
    private sessionService: SessionService
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
}
