import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Empresa } from '../models/empresa.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class EmpresaService {
  private baseUrl = `${environment.apiUrl}/empresas`;

  constructor(private http: HttpClient) {}

  list(): Observable<Empresa[]> {
    return this.http.get<Empresa[]>(this.baseUrl);
  }

  get(id: number): Observable<Empresa> {
    return this.http.get<Empresa>(`${this.baseUrl}/${id}`);
  }

  create(data: Partial<Empresa>): Observable<Empresa> {
    return this.http.post<Empresa>(this.baseUrl, data);
  }

  update(id: number, data: Partial<Empresa>): Observable<Empresa> {
    return this.http.put<Empresa>(`${this.baseUrl}/${id}`, data);
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }
}
