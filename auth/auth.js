const loginSelectorButton = document.getElementById("auth-select-login");
const registerSelectorButton = document.getElementById("auth-select-register");
const loginContainer = document.getElementById("login-container");
const registerContainer = document.getElementById("register-container");

const loginButton = document.getElementById("login-button");
const registerButton = document.getElementById("register-button");


const loginUsernameInput = document.querySelector("#login-container #username-input");
const loginPasswordInput = document.querySelector("#login-container #password-input");

const registerUsernameInput = document.querySelector("#register-container #username-input");
const registerPasswordInput = document.querySelector("#register-container #password-input");
const registerPasswordConfirmInput = document.querySelector("#register-container #password-confirm-input");


function enterButtonCheck(event) {
    if (event.key === "Enter") {
        event.preventDefault();

        if (document.activeElement === loginPasswordInput) {
            loginButton.click();
        } else if (document.activeElement === registerPasswordConfirmInput) {
            registerButton.click();
        }
    }
}

loginPasswordInput.addEventListener("keydown", enterButtonCheck);
registerPasswordConfirmInput.addEventListener("keydown", enterButtonCheck);


function ToggleAuthForm(btn) {
    loginSelectorButton.classList.remove("active");
    registerSelectorButton.classList.remove("active");
    btn.classList.add("active");

    if (btn === loginSelectorButton) {
        loginContainer.classList.add("active");
        registerContainer.classList.remove("active");
    } else if (btn === registerSelectorButton) {
        registerContainer.classList.add("active");
        loginContainer.classList.remove("active");
    }
}

async function Login() {
    if (!loginUsernameInput.value.trim() || !loginPasswordInput.value.trim()) {
        return;
    }

    const req = await fetch("../api/user_login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            username: loginUsernameInput.value, 
            password: loginPasswordInput.value 
        })
    });

    const res = await req.json();
    if (res.success) {
        window.location.href = res.redirect;
    }
}

async function Register() {
    if (!registerUsernameInput.value.trim() || !registerPasswordInput.value.trim() || !registerPasswordConfirmInput.value.trim()) {
        return;
    }

    const req = await fetch("../api/user_register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            username: registerUsernameInput.value, 
            password: registerPasswordInput.value, 
            password2: registerPasswordConfirmInput.value 
        }) 
    });

    const res = await req.json();
    if (res.success) {
        window.location.href = res.redirect;
    }
}