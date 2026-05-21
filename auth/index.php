<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth.css">
    <script src="auth.js" defer></script>
    <title>Authentication</title>
</head>
<body>
    <h1>Authentication</h1>
    <div id="auth-container">
        <div id="auth-select-container">
            <div id="auth-select-login" class="selector active" onclick="ToggleAuthForm(this)">Login</div>
            <div id="auth-select-register" class="selector" onclick="ToggleAuthForm(this)">Register</div>
        </div>

        <div id="login-container" class="active form-container">
            <div class="form-field">
                <p class="field-name">Username</p>
                <input id="username-input" type="text" />
            </div>

            <div class="form-field">
                <p class="field-name">Password</p>
                <input id="password-input" type="password" />
            </div>

            <button onclick="Login()">Login</button>
        </div>

        <div id="register-container" class="form-container">
            <div class="form-field">
                <p class="field-name">Username</p>
                <input id="username-input" type="text" />
            </div>

            <div class="form-field">
                <p class="field-name">Password</p>
                <input id="password-input" type="password" />
            </div>

            <div class="form-field">
                <p class="field-name">Confirm password</p>
                <input id="password-confirm-input" type="password">
            </div>

            <button onclick="Register()">Register</button>
        </div>
    </div>
</body>
</html>