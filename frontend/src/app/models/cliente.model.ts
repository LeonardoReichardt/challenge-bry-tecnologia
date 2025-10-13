import { Empresa } from "./empresa.model";

export interface Cliente {
  id?: number;
  login: string;
  nome: string;
  cpf: string;
  email: string;
  endereco?: string;
  senha?: string;
  documento?: string;
  empresas?: Empresa[];
}
