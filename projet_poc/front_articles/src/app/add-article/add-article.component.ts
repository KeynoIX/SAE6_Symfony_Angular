import { Component, OnInit } from '@angular/core';
import { Article } from '../models/article';
import { Categorie } from '../models/categorie';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-add-article',
  standalone: false,
  templateUrl: './add-article.component.html',
  styleUrl: './add-article.component.css'
})
export class AddArticleComponent implements OnInit {
  article: Article = new Article(0, '', '', new Date(), false, new Categorie(0, '', '', 0));
  categories: Categorie[] = [];
  info: string = "";

  constructor(private apiService: ApiService) { }

  ngOnInit(): void {
    this.apiService.getCategories().subscribe((data: Categorie[]) => {
      this.categories = data;
    });
  }

  onSubmit() {
    this.apiService.addArticle(this.article).subscribe(result => {
      console.log('Article ajouté', result);
      this.info = 'Article No ' + result.id + ' créé';
    });
  }
}