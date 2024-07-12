const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

function recoverPassword() {
    alert("Revisa tu correo electrónico para obtener instrucciones sobre cómo recuperar tu contraseña.");
}