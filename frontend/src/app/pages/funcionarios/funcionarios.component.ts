import { Component, OnInit } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';

import { FuncionarioService } from '../../services/funcionario.service';
import { Funcionario } from '../../models/funcionario.model';

@Component({
  selector: 'app-funcionarios',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './funcionarios.component.html',
})

export class FuncionariosComponent implements OnInit {

  funcionarios: Funcionario[] = [];
  loading = true;

  constructor(private funcionarioService: FuncionarioService, private router: Router) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.loading = true;

    this.funcionarioService.list().subscribe({
      next: (data) => { this.funcionarios = data; this.loading = false; },
      error: (err) => { console.error(err); this.loading = false; }
    });
  }

  delete(id: number) {
    if(confirm('Tem certeza que deseja excluir este funcionário?')) {
      this.funcionarioService.delete(id).subscribe(() => this.load());
    }
  }

}
