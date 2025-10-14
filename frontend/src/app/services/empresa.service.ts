import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { Empresa } from '../models/empresa.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})

export class EmpresaService {
  private baseUrl = `${environment.apiUrl}/empresas`;

  constructor(private http: HttpClient) {}

  list(): Observable<Empresa[]> {
    return this.http.get<{ data: Empresa[] }>(this.baseUrl).pipe(
      map(response => response.data)
    );
  }

  get(id: number): Observable<Empresa> {
    return this.http.get<{ data: Empresa }>(`${this.baseUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  create(data: Partial<Empresa>): Observable<Empresa> {
    return this.http.post<{ data: Empresa }>(this.baseUrl, data).pipe(
      map(response => response.data)
    );
  }

  update(id: number, data: Partial<Empresa>): Observable<Empresa> {
    return this.http.put<{ data: Empresa }>(`${this.baseUrl}/${id}`, data).pipe(
      map(response => response.data)
    );
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }
}
