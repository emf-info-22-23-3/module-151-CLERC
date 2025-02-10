const container = document.querySelector('.container');
const switchToRegister = document.getElementById('switchToRegister');
const switchToLogin = document.getElementById('switchToLogin');

switchToRegister.addEventListener('click', () => {
    container.classList.add('register-mode');
});

switchToLogin.addEventListener('click', () => {
    container.classList.remove('register-mode');
});