import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl } from '@angular/forms';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  registerForm!: FormGroup;
  submitted = false;

  // Pourcentage de la force du mot de passe
  passwordStrength = 0;

  // Indique si on refuse l'inscription faute de force
  forceInsuffisante = false;

  constructor(private fb: FormBuilder) {}

  ngOnInit(): void {
    this.registerForm = this.fb.group({
      prenom: ['', Validators.required],
      nom: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
      confirmPassword: ['', Validators.required],
      terms: [false, Validators.requiredTrue]
    }, {
      validators: [this.passwordMatchValidator]
    });

    // Calcul de la force du mot de passe à chaque changement
    this.registerForm.get('password')?.valueChanges.subscribe(value => {
      this.passwordStrength = this.calculatePasswordStrength(value);
      // Réinitialise l'erreur de force si l'utilisateur retape
      if (this.forceInsuffisante && this.passwordStrength >= 70) {
        this.forceInsuffisante = false;
      }
    });
  }

  // Exemple d'un calcul plus élaboré
  // +20% si longueur >= 8
  // +10% si longueur >= 12
  // +10% si longueur >= 16
  // +15% si contient une majuscule
  // +15% si contient un chiffre
  // +15% si contient un caractère spécial
  calculatePasswordStrength(password: string): number {
    let strength = 0;

    if (password.length >= 8)  { strength += 20; }
    if (password.length >= 12) { strength += 10; }
    if (password.length >= 16) { strength += 10; }

    if (/[A-Z]/.test(password))  { strength += 15; }
    if (/\d/.test(password))      { strength += 15; }
    if (/[!@#$&*]/.test(password)) { strength += 15; }

    // Cap à 100%
    return Math.min(strength, 100);
  }

  // Validator personnalisé : mot de passe = confirmation
  passwordMatchValidator(form: AbstractControl): null {
    const password = form.get('password')?.value;
    const confirmPassword = form.get('confirmPassword')?.value;
    if (password !== confirmPassword) {
      form.get('confirmPassword')?.setErrors({ mismatch: true });
    } else {
      const errors = form.get('confirmPassword')?.errors;
      if (errors) {
        delete errors['mismatch'];
        if (Object.keys(errors).length === 0) {
          form.get('confirmPassword')?.setErrors(null);
        }
      }
    }
    return null;
  }

  get f() {
    return this.registerForm.controls;
  }

  onSubmit(): void {
    this.submitted = true;

    // Vérification standard des champs requis, etc.
    if (this.registerForm.invalid) {
      return;
    }

    // Vérification force du mot de passe
    if (this.passwordStrength < 70) {
      this.forceInsuffisante = true;
      return;
    }

    console.log('Inscription réussie : ', this.registerForm.value);
    // Ici, vous pouvez gérer la logique d’inscription (ex: appel API)
  }
}
