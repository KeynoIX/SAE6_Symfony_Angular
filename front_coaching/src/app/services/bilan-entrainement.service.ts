import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BilanEntrainementService {
  private baseUrl = 'http://127.0.0.1:8008/api';

  constructor(private http: HttpClient) {}

  getBilanEntrainement(sportifId: number, dateMin: string, dateMax: string): Observable<any> {
    return this.http.get(`${this.baseUrl}/bilan-entrainement/${sportifId}?date_min=${dateMin}&date_max=${dateMax}`);
  }
}
