const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

window.switchAuth = switchAuth;
window.returnAuth = returnAuth;
window.logout = logout;
window.returnLogout = returnLogout;

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
})

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
})

window.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const mode = params.get('mode');
  const container = document.querySelector('.container');

  if (mode === 'register') {
    container.classList.add('active');
  } else {
    container.classList.remove('active');
  }
});

function switchAuth() {
  const navAuth = document.querySelector('.not-active-user');
  navAuth.classList.add('active-user');
  navAuth.classList.remove('not-active-user');
}

function returnAuth() {
  const navAuth = document.querySelector('.active-user');
  navAuth.classList.add('not-active-user');
  navAuth.classList.remove('active-user');
}

function logout() {
  const navAuth = document.querySelector('.logout-not-active');
  navAuth.classList.add('logout-active');
  navAuth.classList.remove('logout-not-active');
}

function returnLogout() {
  const navAuth = document.querySelector('.logout-active');
  navAuth.classList.add('logout-not-active');
  navAuth.classList.remove('logout-active');
}

function validateUsername(input) {
  const warning = document.getElementById('usernameWarning');
  if (input.value.length < 10 || input.value.length > 15) {
    warning.style.display = 'block';
    input.setCustomValidity("Username must be between 10 and 15 characters.");
  } else {
    warning.style.display = 'none';
    input.setCustomValidity("");
  }
}
