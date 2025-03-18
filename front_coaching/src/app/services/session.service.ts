import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SessionService {
  private baseUrl = 'https://127.0.0.1:8008/api'; // Adaptez selon votre configuration

  constructor(private http: HttpClient) {}

  // Récupère la liste de toutes les sessions
  getSessions(): Observable<any> {
    return this.http.get(`${this.baseUrl}/seances`); // L'endpoint backend reste "seances"
  }

  // Récupère le détail d'une session par son ID
  getSessionById(id: number): Observable<any> {
    return this.http.get(`${this.baseUrl}/seance/${id}`);
  }
}
