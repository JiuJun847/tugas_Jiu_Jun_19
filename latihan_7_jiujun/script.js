const togglePassword = document.getElementById('toggle-password');
const passwordField = document.getElementById('password');

passwordField.addEventListener('input', () => {
    togglePassword.style.display = passwordField.value.length > 0 ? 'block' : 'none';
});

togglePassword.addEventListener('click', () => {
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        togglePassword.src = "assets/mata terbuka.png";
    } else {
        passwordField.type = 'password';
        togglePassword.src = "assets/mata tertutup.png";
    }``
});
