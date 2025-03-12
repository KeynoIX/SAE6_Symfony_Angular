import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { CategoriesListComponent } from './categories-list/categories-list.component';
import { ArticlesListComponent } from './articles-list/articles-list.component';
import { AddArticleComponent } from './add-article/add-article.component';

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'categories', component: CategoriesListComponent },
  { path: 'articles', component: ArticlesListComponent },
  { path: 'add-article', component: AddArticleComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }