import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['inputLogo', 'theme1', 'theme2', 'title'];

    switchdefault() {
        console.log('switchdefault');
        
        this.theme1Target.classList.remove('d-none');
        this.theme2Target.classList.add('d-none');
        this.inputLogoTarget.classList.add('d-none');
    }

    switchalternative() {
        this.theme1Target.classList.add('d-none');
        this.theme2Target.classList.remove('d-none');
        this.inputLogoTarget.classList.remove('d-none');
    }
}