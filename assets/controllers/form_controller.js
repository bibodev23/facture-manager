import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
	static targets = ['step1', 'step2', 'step3', 'siret', 'siretError'];
	nextStep() {
		this.step1Target.classList.toggle('d-none');
		this.step2Target.classList.toggle('d-none');
        if (this.step1Target.classList.contains('d-none')) {
            this.step3Target.innerHTML = 'Revenir à mon entreprise';
        } else {
			this.step3Target.innerHTML = 'Finaliser mon inscription';
		}
	}

	validateSiret() {
        const value = this.siretTarget.value.trim();
        const regex = /^\d{14}$/;

        if (!regex.test(value)) {
            this.siretErrorTarget.textContent = "Le SIRET doit contenir exactement 14 chiffres.";
            this.siretTarget.classList.add('is-invalid');
        } else {
            this.siretErrorTarget.textContent = "";
            this.siretTarget.classList.remove('is-invalid');
            this.siretTarget.classList.add('is-valid');
        }
    }
}
