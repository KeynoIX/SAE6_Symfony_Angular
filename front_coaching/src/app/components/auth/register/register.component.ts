import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl } from '@angular/forms';
import { SportifService } from '../../../services/sportif.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  registerForm!: FormGroup;
  submitted = false;

  constructor(private fb: FormBuilder, private sportifService: SportifService) {}

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
  }

  // Validator personnalisé pour vérifier que le mot de passe correspond à la confirmation
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

  // Accès aux contrôles du formulaire
  get f() {
    return this.registerForm.controls;
  }

  onSubmit(): void {
    this.submitted = true;
    if (this.registerForm.invalid) {
      return;
    }
    const payload = {
      email: this.registerForm.value.email,
      password: this.registerForm.value.password,
      prenom: this.registerForm.value.prenom,
      nom: this.registerForm.value.nom,
      // Vous pouvez ajouter ici le niveau sportif si besoin
      niveau_sportif: 'Débutant'
    };
    this.sportifService.registerSportif(payload).subscribe({
      next: (response: any) => {
        console.log('Inscription réussie : ', response);
        alert('Inscription réussie !');
        // Rediriger l'utilisateur ou effectuer d'autres actions
      },
      error: (error: any) => {
        console.error('Erreur lors de l’inscription : ', error);
        alert('Erreur lors de l’inscription');
      }
    });
  }
}
