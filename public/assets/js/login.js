document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    if (!form) return;

    const inputs = form.querySelectorAll('input[required]');

    const validateInput = (input) => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            return false;
        }
        input.classList.remove('is-invalid');
        return true;
    };

    inputs.forEach((input) => {
        input.addEventListener('blur', () => validateInput(input));
        input.addEventListener('input', () => {
            if (input.value.trim()) input.classList.remove('is-invalid');
        });
    });

    form.addEventListener('submit', (e) => {
        let valid = true;
        inputs.forEach((input) => { if (!validateInput(input)) valid = false; });

        if (!valid) {
            e.preventDefault();
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });
});