import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';

import { EmpresaService } from '../../services/empresa.service';

@Component({
  selector: 'app-empresa-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './empresa-form.component.html',
})

export class EmpresaFormComponent implements OnInit {

  form!: FormGroup;
  isEdit = false;
  empresaId?: number;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private empresaService: EmpresaService
  ) {}

  ngOnInit() {
    this.form = this.fb.group({
      nome: ['', Validators.required],
      cnpj: ['', Validators.required],
      endereco: ['']
    });

    this.empresaId = Number(this.route.snapshot.paramMap.get('id'));

    if(this.empresaId) {
      this.isEdit = true;

      this.empresaService.get(this.empresaId).subscribe(data => {
        this.form.patchValue(data);
      });
    }
  }

  onSubmit() {
    if(this.form.invalid) {
      return;
    }

    if(this.isEdit) {
      this.empresaService.update(this.empresaId!, this.form.value).subscribe(() => {
        this.router.navigate(['/empresas']);
      });
    }
    else {
      this.empresaService.create(this.form.value).subscribe(() => {
        this.router.navigate(['/empresas']);
      });
    }
  }

}
