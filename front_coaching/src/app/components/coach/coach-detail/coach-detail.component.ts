import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CoachService } from '../../../services/coach.service';

@Component({
  selector: 'app-coach-detail',
  templateUrl: './coach-detail.component.html',
  styleUrls: ['./coach-detail.component.scss']
})
export class CoachDetailComponent implements OnInit {
  coach: any;
  loading = false;
  error = '';

  constructor(private route: ActivatedRoute, private coachService: CoachService) { }

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.loading = true;
      this.coachService.getCoachById(+id).subscribe({
        next: (data: any) => {
          this.coach = data;
          this.loading = false;
        },
        error: (err: any) => {
          console.error('Erreur lors du chargement du coach', err);
          this.error = 'Erreur lors du chargement du coach';
          this.loading = false;
        }
      });
    } else {
      this.error = 'ID de coach non fourni.';
    }
  }
}
