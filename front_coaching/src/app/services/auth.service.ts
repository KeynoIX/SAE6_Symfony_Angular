// auth.service.ts
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, map } from 'rxjs';

export class AuthUser {
  constructor(
    public id: number | null = null,
    public email: string = "",
    public roles: string[] = []
  ) { }

  isAdmin(): boolean {
    return this.roles.includes("ROLE_ADMIN");
  }

  isCoach(): boolean {
    return this.roles.includes("ROLE_COACH");
  }

  isLogged(): boolean {
    return this.email.length > 0;
  }
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrlLogin = 'http://localhost:8008/api/login';
  private apiUrlUserInfo = 'http://127.0.0.1:8008/api/user/me';
  private localStorageToken = 'currentToken';

  private currentTokenSubject: BehaviorSubject<string | null>;
  public currentToken: Observable<string | null>;
  public get currentTokenValue(): string | null {
    return this.currentTokenSubject.value;
  }

  private currentAuthUserSubject: BehaviorSubject<AuthUser>;
  public currentAuthUser: Observable<AuthUser>;
  public get currentAuthUserValue(): AuthUser {
    return this.currentAuthUserSubject.value;
  }

  constructor(private http: HttpClient) {
    this.currentTokenSubject = new BehaviorSubject<string | null>(null);
    this.currentToken = this.currentTokenSubject.asObservable();
    this.currentAuthUserSubject = new BehaviorSubject(new AuthUser());
    this.currentAuthUser = this.currentAuthUserSubject.asObservable();
  }

  // Méthode pour récupérer un cookie par son nom
  private getCookie(name: string): string | null {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    return parts.length === 2 ? parts.pop()?.split(';').shift() || null : null;
  }

  // Méthode pour définir un cookie avec expiration de 7 jours
  private setCookie(token: string): void {
    const expires = new Date();
    expires.setDate(expires.getDate() + 7);
    document.cookie = `${this.localStorageToken}=${token}; expires=${expires.toUTCString()}; path=/; SameSite=Lax${location.protocol === 'https:' ? '; Secure' : ''}`;
  }

  // Méthode pour supprimer un cookie
  private deleteCookie(): void {
    document.cookie = `${this.localStorageToken}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/`;
  }

  // Cette méthode d'initialisation sera appelée via APP_INITIALIZER
  public initializeToken(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Tente de récupérer le token depuis localStorage ou les cookies
      let token = localStorage.getItem(this.localStorageToken) || this.getCookie(this.localStorageToken);
      console.log("Token récupéré dans initializeToken:", token);
      if (token) {
        this.updateUserInfo(token);
      }
      resolve();
    });
  }

  // Met à jour l'information utilisateur à partir du token, sans réinitialiser inutilement
  private updateUserInfo(token: string | null): void {
    if (token) {
      localStorage.setItem(this.localStorageToken, token);
      this.setCookie(token);
      const headers = new HttpHeaders({ 'Authorization': `Bearer ${token}`, 'skip-token': 'true' });
      this.http.get<any>(this.apiUrlUserInfo, { headers }).subscribe({
        next: data => {
          if (data && data.email) {
            this.currentTokenSubject.next(token);
            this.currentAuthUserSubject.next(new AuthUser(data.id, data.email, data.roles));
          } else {
            this.logout();
          }
        },
        error: err => {
          console.error("Erreur lors de la récupération de l'utilisateur :", err);
          this.logout();
        }
      });
    } else {
      localStorage.removeItem(this.localStorageToken);
      this.deleteCookie();
      this.currentTokenSubject.next(null);
      this.currentAuthUserSubject.next(new AuthUser());
    }
  }

  public login(email: string, password: string): Observable<boolean> {
    return this.http.post<any>(this.apiUrlLogin, { email, password })
      .pipe(map(response => {
        if (response.token) {
          this.updateUserInfo(response.token);
          return true;
        }
        return false;
      }));
  }

  public logout(): void {
    this.updateUserInfo(null);
  }
}
