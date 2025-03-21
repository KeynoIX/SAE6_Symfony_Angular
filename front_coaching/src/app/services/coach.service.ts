import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class CoachService {
  private baseUrl = 'https://127.0.0.1:8008/api'; // Adaptez l'URL à votre configuration

  constructor(private http: HttpClient) {}

  // Récupère la liste des coachs
  getCoachs(): Observable<any> {
    return this.http.get(`${this.baseUrl}/coachs`);
  }

  getCoachById(id: number): Observable<any> {
    return this.http.get(`${this.baseUrl}/coach/${id}`);
  }
}
