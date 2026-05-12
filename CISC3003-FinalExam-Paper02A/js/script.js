document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', (event) => {
        const required = form.querySelectorAll('[required]');
        const missing = Array.from(required).some((field) => {
            if (field.type === 'radio') {
                return !form.querySelector(`[name="${field.name}"]:checked`);
            }
            return !field.value.trim();
        });

        if (missing) {
            event.preventDefault();
            alert('Please complete all required fields before submitting.');
        }
    });
});
