import { Empresa } from "./empresa.model";

export interface Funcionario {
  id?: number;
  login: string;
  nome: string;
  cpf: string;
  email: string;
  endereco?: string;
  senha?: string;
  documento?: string; // URL ou path retornado pelo backend
  empresas?: Empresa[];
}
