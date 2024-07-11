const afficher = document.getElementById('afficher');
const password = document.querySelector('#password');
afficher.addEventListener('click', () => {
    password.type = (password.type === "password") ? "text" : "password";
});ml