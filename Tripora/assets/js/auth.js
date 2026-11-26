document.addEventListener('DOMContentLoaded', () => {
    const T = window.Tripora;
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const showPassword = document.querySelectorAll('[data-toggle-password]');
    showPassword.forEach(button => button.addEventListener('click', () => {
        const field = document.getElementById(button.dataset.togglePassword);
        if (field) {
            field.type = field.type === 'password' ? 'text' : 'password';
            button.innerHTML = field.type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        }
    }));

    loginForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const email = loginForm.email.value.trim();
        const password = loginForm.password.value;
        if (!email || !password) { T.toast('Email and password are required.', 'warning'); return; }
        const button = loginForm.querySelector('[type="submit"]');
        button.disabled = true; button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in';
        try {
            const result = await T.api('auth/login', { method: 'POST', body: { email, password } });
            window.TRIPORA_CSRF = result.data.csrf_token;
            window.location.href = `${T.base}/dashboard.php`;
        } catch (error) {
            T.toast(error.message, 'danger');
        } finally {
            button.disabled = false; button.innerHTML = 'Log in <i class="bi bi-arrow-right ms-2"></i>';
        }
    });

    registerForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const full_name = registerForm.full_name.value.trim();
        const email = registerForm.email.value.trim();
        const phone = registerForm.phone.value.trim();
        const password = registerForm.password.value;
        const confirm = registerForm.confirm_password.value;
        if (!full_name || !email || !password || !confirm) { T.toast('Please complete all required fields.', 'warning'); return; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { T.toast('Please enter a valid email address.', 'warning'); return; }
        if (password.length < 8) { T.toast('Password must contain at least 8 characters.', 'warning'); return; }
        if (password !== confirm) { T.toast('Password confirmation does not match.', 'warning'); return; }
        const button = registerForm.querySelector('[type="submit"]');
        button.disabled = true; button.textContent = 'Creating account...';
        try {
            await T.api('auth/register', { method: 'POST', body: { full_name, email, phone, password } });
            T.toast('Registration successful. Please log in.', 'success');
            window.setTimeout(() => { window.location.href = `${T.base}/login.php?registered=1`; }, 700);
        } catch (error) {
            T.toast(error.message, 'danger');
        } finally {
            button.disabled = false; button.textContent = 'Create account';
        }
    });
});
