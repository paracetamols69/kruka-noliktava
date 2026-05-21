const loginSelectorButton = document.getElementById("auth-select-login");
const registerSelectorButton = document.getElementById("auth-select-register");
const loginContainer = document.getElementById("login-container");
const registerContainer = document.getElementById("register-container");

function ToggleAuthForm(btn) {
    loginSelectorButton.classList.remove("active");
    registerSelectorButton.classList.remove("active");

    btn.classList.add("active");

    switch (btn) {
        case loginSelectorButton:
            loginContainer.classList.add("active");
            registerContainer.classList.remove("active");
            break;

        case registerSelectorButton:
            registerContainer.classList.add("active");
            loginContainer.classList.remove("active");
    }
}

async function Login() {
    const usernameInput = document.querySelector("#login-container #username-input");
    const passwordInput = document.querySelector("#login-container #password-input");

    if (!usernameInput.value.trim() || !passwordInput.value.trim()) {
        return;
    }

    const req = await fetch("../api/user_login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username: usernameInput.value, password: passwordInput.value })
    });

    const res = await req.json();
    if (res.success) 
        window.location.href = res.redirect;
}

async function Register() {
    usernameInput = document.querySelector("#register-container #username-input");
    passwordInput = document.querySelector("#register-container #password-input");
    passwordConfirmInput = document.querySelector("#register-container #password-confirm-input");

    const req = await fetch("../api/user_register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            username: usernameInput.value, 
            password: passwordInput.value, 
            password2: passwordConfirmInput.value 
        }) 
    });

    const res = await req.json();
    if (res.success) 
        window.location.href = res.redirect;
}