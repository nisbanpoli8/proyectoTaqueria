document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');

    if (!form) {
        return;
    }

    const inputs = form.querySelectorAll('input[required]');

    inputs.forEach((input) => {
        const setInvalidState = () => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                return;
            }

            input.classList.remove('is-invalid');
        };

        input.addEventListener('blur', setInvalidState);
        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('is-invalid');
            }
        });
    });
});
