import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../../../services/auth.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  user: any;
  loading: boolean = true;
  error: string = '';

  // Rendre 'authService' public pour qu'il soit accessible dans le template
  constructor(public authService: AuthService) { }

  ngOnInit(): void {
    const currentUser = this.authService.currentAuthUserValue;
    if (!currentUser || !currentUser.id) {
      this.error = 'Utilisateur non connecté.';
      this.loading = false;
      return;
    }
    // Récupérer les informations de l'utilisateur à partir de AuthService
    this.user = currentUser;
    this.loading = false;
  }

  updateProfileImage(newImage: string): void {
    // Logique pour changer l'image de profil (à implémenter si nécessaire)
    console.log("Changement d'image en : ", newImage);
  }
}
