import { Component, OnInit } from '@angular/core';
import { Article } from '../models/article';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-articles-list',
  standalone: false,
  templateUrl: './articles-list.component.html',
  styleUrl: './articles-list.component.css'
})
export class ArticlesListComponent implements OnInit {
  articles: Article[] = [];

  constructor(private apiService: ApiService) { }

  ngOnInit(): void {
    this.apiService.getArticles().subscribe((data: Article[]) => {
      this.articles = data;
    });
  }
}