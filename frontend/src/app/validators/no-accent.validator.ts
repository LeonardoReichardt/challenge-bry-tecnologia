import { AbstractControl, ValidationErrors } from '@angular/forms';

export function noAccentValidator(control: AbstractControl): ValidationErrors | null {
  const value = control.value as string;

  if(/[áéíóúâêîôûãõç]/i.test(value)) {
    return { accentNotAllowed: true };
  }

  return null;
}
