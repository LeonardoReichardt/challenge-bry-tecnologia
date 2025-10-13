import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ClienteService } from '../../services/cliente.service';
import { noAccentValidator } from '../../validators/no-accent.validator';

@Component({
  selector: 'app-cliente-form',
  templateUrl: './cliente-form.component.html'
})

export class ClienteFormComponent implements OnInit {

  form!: FormGroup;
  isEdit = false;
  clienteId?: number;
  selectedFile?: File;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private clienteService: ClienteService
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

    this.clienteId = Number(this.route.snapshot.paramMap.get('id'));

    if(this.clienteId) {
      this.isEdit = true;

      this.clienteService.get(this.clienteId).subscribe(data => {
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
      this.clienteService.update(this.clienteId!, fd).subscribe(() => {
        this.router.navigate(['/clientes']);
      });
    }
    else {
      this.clienteService.create(fd).subscribe(() => {
        this.router.navigate(['/clientes']);
      });
    }
  }

}
