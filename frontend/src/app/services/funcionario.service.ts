import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Funcionario } from '../models/funcionario.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class FuncionarioService {
  private baseUrl = `${environment.apiUrl}/funcionarios`;

  constructor(private http: HttpClient) {}

  list(): Observable<Funcionario[]> {
    return this.http.get<Funcionario[]>(this.baseUrl);
  }

  get(id: number): Observable<Funcionario> {
    return this.http.get<Funcionario>(`${this.baseUrl}/${id}`);
  }

  create(data: FormData | Partial<Funcionario>): Observable<Funcionario> {
    return this.http.post<Funcionario>(this.baseUrl, data);
  }

  update(id: number, data: FormData | Partial<Funcionario>): Observable<Funcionario> {
    return this.http.put<Funcionario>(`${this.baseUrl}/${id}`, data);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }
}
