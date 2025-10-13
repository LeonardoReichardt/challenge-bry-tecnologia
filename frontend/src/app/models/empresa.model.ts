import { Cliente } from "./cliente.model";
import { Funcionario } from "./funcionario.model";

export interface Empresa {
  id?: number;
  nome: string;
  cnpj: string;
  endereco?: string;
  funcionarios?: Funcionario[];
  clientes?: Cliente[];
}
