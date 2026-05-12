document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('#registerForm');
    const loginForm = document.querySelector('#loginForm');
    const email = document.querySelector('#email');
    const emailStatus = document.querySelector('#emailStatus');

    [registerForm, loginForm].forEach((form) => {
        if (!form) return;
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }
            const password = form.querySelector('#password');
            const confirmation = form.querySelector('#password_confirmation');
            if (confirmation) {
                confirmation.setCustomValidity('');
            }
            if (password && confirmation && password.value !== confirmation.value) {
                event.preventDefault();
                confirmation.setCustomValidity('Passwords do not match.');
                confirmation.reportValidity();
            }
        });
    });

    if (registerForm && email && emailStatus) {
        let timer = null;
        email.addEventListener('input', () => {
            clearTimeout(timer);
            emailStatus.textContent = 'Checking email...';
            timer = setTimeout(async () => {
                if (!email.validity.valid) {
                    emailStatus.textContent = 'Please enter a valid email.';
                    return;
                }
                const response = await fetch(`php/check_email.php?email=${encodeURIComponent(email.value)}`);
                const data = await response.json();
                emailStatus.textContent = data.message;
                emailStatus.style.color = data.available ? '#047857' : '#9f1239';
            }, 350);
        });
    }

    document.querySelectorAll('.service-toggle').forEach((button) => {
        button.addEventListener('click', () => {
            const panel = document.getElementById(button.dataset.target);
            if (!panel) return;
            panel.hidden = !panel.hidden;
            button.textContent = panel.hidden ? 'Show Details' : 'Hide Details';
            if (button.dataset.target === 'courseTools' && !panel.hidden) {
                button.textContent = 'Hide Services';
            } else if (button.dataset.target === 'courseTools') {
                button.textContent = 'Manage Services';
            }
        });
    });
});
