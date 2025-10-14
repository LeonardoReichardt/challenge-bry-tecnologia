import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { Funcionario } from '../models/funcionario.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})

export class FuncionarioService {
  private baseUrl = `${environment.apiUrl}/funcionarios`;

  constructor(private http: HttpClient) {}

  list(): Observable<Funcionario[]> {
    return this.http.get<{ data: Funcionario[] }>(this.baseUrl).pipe(
      map(response => response.data)
    );
  }

  get(id: number): Observable<Funcionario> {
    return this.http.get<{ data: Funcionario }>(`${this.baseUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  create(data: FormData | Partial<Funcionario>): Observable<Funcionario> {
    return this.http.post<{ data: Funcionario }>(this.baseUrl, data).pipe(
      map(response => response.data)
    );
  }

  update(id: number, data: FormData | Partial<Funcionario>): Observable<Funcionario> {
    let body: any;

    if(data instanceof FormData) {
      data.append('_method', 'PUT');
      body = data;
    }
    else {
      body = { ...data, _method: 'PUT' };
    }

    return this.http.post<{ data: Funcionario }>(`${this.baseUrl}/${id}`, body).pipe(
      map(response => response.data)
    );
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }
}
