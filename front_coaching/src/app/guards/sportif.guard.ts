import { Injectable } from '@angular/core';
import { CanActivate, Router, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class SportifGuard implements CanActivate {
  constructor(private authService: AuthService, private router: Router) {}

  canActivate(): boolean | UrlTree | Observable<boolean | UrlTree> | Promise<boolean | UrlTree> {
    const user = this.authService.currentAuthUserValue;
    // Vérifier que l'utilisateur est connecté et qu'il a le rôle 'ROLE_SPORTIF'
    if (user.isLogged() && user.roles.includes('ROLE_SPORTIF')) {
      return true;
    }
    // Sinon, rediriger vers la page de connexion (ou une page d'erreur)
    return this.router.parseUrl('/login');
  }
}
