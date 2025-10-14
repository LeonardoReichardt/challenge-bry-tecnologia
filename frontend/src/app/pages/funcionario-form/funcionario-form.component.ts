import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';

import { FuncionarioService } from '../../services/funcionario.service';
import { noAccentValidator } from '../../validators/no-accent.validator';

@Component({
  selector: 'app-funcionario-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './funcionario-form.component.html'
})

export class FuncionarioFormComponent implements OnInit {

  form!: FormGroup;
  isEdit = false;
  funcionarioId?: number;
  selectedFile?: File;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private funcionarioService: FuncionarioService
  ) {}

  ngOnInit() {
    this.form = this.fb.group({
      login: ['', [Validators.required, noAccentValidator]],
      nome: ['', [Validators.required, noAccentValidator]],
      cpf: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      endereco: [''],
      senha: ['', Validators.required]
    });

    this.funcionarioId = Number(this.route.snapshot.paramMap.get('id'));

    if(this.funcionarioId) {
      this.isEdit = true;

      this.funcionarioService.get(this.funcionarioId).subscribe(data => {
        this.form.patchValue(data);
      });
    }
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];

    if(file && (file.type === 'application/pdf' || file.type === 'image/jpeg')) {
      this.selectedFile = file;
    }
    else {
      alert('Apenas PDF ou JPG são permitidos');
    }
  }

  onSubmit() {
    if(this.form.invalid) {
      return;
    }

    const fd = new FormData();

    Object.entries(this.form.value).forEach(([key, value]) => {
      fd.append(key, value as string);
    });

    if(this.selectedFile) {
      fd.append('documento', this.selectedFile);
    }

    if(this.isEdit) {
      this.funcionarioService.update(this.funcionarioId!, fd).subscribe(() => {
        this.router.navigate(['/funcionarios']);
      });
    }
    else {
      this.funcionarioService.create(fd).subscribe(() => {
        this.router.navigate(['/funcionarios']);
      });
    }
  }

}
