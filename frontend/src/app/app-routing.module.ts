import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { EmpresasComponent } from './pages/empresas/empresas.component';
import { EmpresaFormComponent } from './pages/empresa-form/empresa-form.component';

import { FuncionariosComponent } from './pages/funcionarios/funcionarios.component';
import { FuncionarioFormComponent } from './pages/funcionario-form/funcionario-form.component';

import { ClientesComponent } from './pages/clientes/clientes.component';
import { ClienteFormComponent } from './pages/cliente-form/cliente-form.component';

const routes: Routes = [
  { path: '', redirectTo: '/empresas', pathMatch: 'full' },

  // Empresas
  { path: 'empresas', component: EmpresasComponent },
  { path: 'empresas/novo', component: EmpresaFormComponent },
  { path: 'empresas/:id', component: EmpresaFormComponent },

  // Funcionários
  { path: 'funcionarios', component: FuncionariosComponent },
  { path: 'funcionarios/novo', component: FuncionarioFormComponent },
  { path: 'funcionarios/:id', component: FuncionarioFormComponent },

  // Clientes
  { path: 'clientes', component: ClientesComponent },
  { path: 'clientes/novo', component: ClienteFormComponent },
  { path: 'clientes/:id', component: ClienteFormComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
