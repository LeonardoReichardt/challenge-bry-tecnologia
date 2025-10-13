import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { EmpresaService } from '../../services/empresa.service';
import { Empresa } from '../../models/empresa.model';

@Component({
  selector: 'app-empresas',
  templateUrl: './empresas.component.html'
})

export class EmpresasComponent implements OnInit {

  empresas: Empresa[] = [];
  loading = true;

  constructor(private empresaService: EmpresaService, private router: Router) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.loading = true;

    this.empresaService.list().subscribe({
      next: (data) => { this.empresas = data; this.loading = false; },
      error: (err) => { console.error(err); this.loading = false; }
    });
  }

  delete(id: number) {
    if(confirm('Tem certeza que deseja excluir esta empresa?')) {
      this.empresaService.delete(id).subscribe(() => this.load());
    }
  }

}
