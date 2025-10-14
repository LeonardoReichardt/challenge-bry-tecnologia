import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { Cliente } from '../models/cliente.model';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})

export class ClienteService {
  private baseUrl = `${environment.apiUrl}/clientes`;

  constructor(private http: HttpClient) {}

  list(): Observable<Cliente[]> {
    return this.http.get<{ data: Cliente[] }>(this.baseUrl).pipe(
      map(response => response.data)
    );
  }

  get(id: number): Observable<Cliente> {
    return this.http.get<{ data: Cliente }>(`${this.baseUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  create(data: FormData | Partial<Cliente>): Observable<Cliente> {
    return this.http.post<{ data: Cliente }>(this.baseUrl, data).pipe(
      map(response => response.data)
    );
  }

  update(id: number, data: FormData | Partial<Cliente>): Observable<Cliente> {
    let body: any;

    if(data instanceof FormData) {
      data.append('_method', 'PUT');
      body = data;
    }
    else {
      body = { ...data, _method: 'PUT' };
    }

    return this.http.post<{ data: Cliente }>(`${this.baseUrl}/${id}`, body).pipe(
      map(response => response.data)
    );
  }

  delete(id: number): Observable<void> {
    return this.http.delete<void>(`${this.baseUrl}/${id}`);
  }
}
