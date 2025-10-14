import { Component, OnInit } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';

import { ClienteService } from '../../services/cliente.service';
import { Cliente } from '../../models/cliente.model';

@Component({
  selector: 'app-clientes',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './clientes.component.html',
})

export class ClientesComponent implements OnInit {

  clientes: Cliente[] = [];
  loading = true;

  constructor(private clienteService: ClienteService, private router: Router) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.loading = true;

    this.clienteService.list().subscribe({
      next: (data) => { this.clientes = data; this.loading = false; },
      error: (err) => { console.error(err); this.loading = false; }
    });
  }

  delete(id: number) {
    if(confirm('Tem certeza que deseja excluir este cliente?')) {
      this.clienteService.delete(id).subscribe(() => this.load());
    }
  }
}
