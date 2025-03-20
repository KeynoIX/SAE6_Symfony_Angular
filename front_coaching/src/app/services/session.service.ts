// session.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SessionService {
  private baseUrl = 'http://127.0.0.1:8008/api'; // Adapté à ta config

  constructor(private http: HttpClient) {}

  getSessions(): Observable<any> {
    return this.http.get(`${this.baseUrl}/seances`);
  }

  getSessionById(id: number): Observable<any> {
    return this.http.get(`${this.baseUrl}/seance/${id}`);
  }

  inscrireSportif(sessionId: number): Observable<any> {
    return this.http.post(`${this.baseUrl}/seance/${sessionId}/inscription`, {});
  }

  // Mise à jour de la méthode pour désinscrire le sportif
  cancelReservation(sessionId: number): Observable<any> {
    return this.http.delete(`${this.baseUrl}/seance/${sessionId}/desinscription`);
  }
}
