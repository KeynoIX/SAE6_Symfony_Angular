import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SportifService {
  private baseUrl = 'http://localhost:8000/api'; // Adaptez à votre config Symfony

  constructor(private http: HttpClient) {}

  registerSportif(payload: {
    email: string;
    password: string;
    prenom: string;
    nom: string;
    niveau_sportif: string;
  }): Observable<any> {
    return this.http.post(`${this.baseUrl}/sportif`, payload);
  }
}
