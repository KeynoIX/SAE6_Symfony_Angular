import { Categorie } from "./categorie";

export class Article {
    constructor(
        public id: number,
        public titre: string,
        public description: string,
        public date_publication: Date,
        public publie: boolean = false, // Valeur par défaut
        public categorie: Categorie
    ) { }
}