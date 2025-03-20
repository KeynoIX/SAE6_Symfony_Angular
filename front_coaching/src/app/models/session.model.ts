export interface Session {
    id: number;
    date_heure: string;
    theme_seance: string;
    type_seance: string;
    niveau_seance: string;
    statut: string;
    coachId?: {
      prenom: string;
      nom: string;
    }
  }
  