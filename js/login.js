let demoCredentials = [

    { email: "admin@email.com", password: "Admin@123" },
    { email: "hr@email.com", password: "Hr@123" }

];

let failedAttempts = parseInt(localStorage.getItem("failedAttempts") || "0", 10);
let lockoutActive = false;

document.addEventListener("DOMContentLoaded", function() {

    let savedEmail = localStorage.getItem("rememberedEmail");

    if (savedEmail) {

        document.getElementById("id01").value = savedEmail;
        document.getElementById("id03").checked = true;

    }

    document.getElementById("id20").addEventListener("click", togglePassword);
    document.getElementById("id18").addEventListener("submit", handleLogin);
    document.getElementById("id01").addEventListener("blur", validateLoginEmail);

    let lockoutEndTime = parseInt(localStorage.getItem("lockoutEndTime") || "0", 10);
    let now = Date.now();
    if (lockoutEndTime > now) {
        let secondsLeft = Math.ceil((lockoutEndTime - now) / 1000);
        let errorBox = document.getElementById("id19");
        errorBox.classList.remove("d-none");
        startLockout(errorBox, secondsLeft);
    } else {
        localStorage.removeItem("lockoutEndTime");
        if (failedAttempts >= 3) {
            failedAttempts = 0;
            localStorage.setItem("failedAttempts", failedAttempts);
        }
    }

});

function togglePassword() {

    let passwordField = document.getElementById("id02");
    let toggleButton = document.getElementById("id20");

    if (passwordField.type === "password") {

        passwordField.type = "text";
        toggleButton.innerHTML = '<i class="bi bi-eye-slash"></i>';

    } else {

        passwordField.type = "password";
        toggleButton.innerHTML = '<i class="bi bi-eye"></i>';

    }

}

function validateLoginEmail() {
    let input = document.getElementById("id01");
    let errorEl = document.getElementById("id01err");
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let value = input.value.trim();

    if (!regex.test(value)) {
        input.classList.remove("class20");
        input.classList.add("class21");
        errorEl.innerHTML = "Please enter a valid email address.";
        return false;
    }

    input.classList.remove("class21");
    input.classList.add("class20");
    errorEl.innerHTML = "";
    return true;
}

function handleLogin(event) {

    event.preventDefault();

    if (lockoutActive) {
        return;
    }

    if (!validateLoginEmail()) {
        return;
    }

    let email = document.getElementById("id01").value.trim();
    let password = document.getElementById("id02").value;
    let rememberMe = document.getElementById("id03").checked;
    let errorBox = document.getElementById("id19");

    let matchedUser = demoCredentials.find(function(user) {
        return user.email === email && user.password === password;
    });

    if (!matchedUser) {
        let employees = JSON.parse(localStorage.getItem("employees")) || [];
        matchedUser = employees.find(function(user) {
            return user.email === email && user.password === password;
        });
    }

    if (matchedUser) {

        failedAttempts = 0;
        localStorage.setItem("failedAttempts", failedAttempts);

        sessionStorage.setItem("loggedIn", "true");
        sessionStorage.setItem("username", email);

        if (rememberMe) {
            localStorage.setItem("rememberedEmail", email);
        } else {
            localStorage.removeItem("rememberedEmail");
        }

        window.location.href = "dashboard.html";

    } else {

        failedAttempts++;
        localStorage.setItem("failedAttempts", failedAttempts);

        errorBox.classList.remove("d-none");
        errorBox.innerHTML = "Invalid email or password.";

        if (failedAttempts >= 3) {
            startLockout(errorBox, 30);
        }

    }

}

function startLockout(errorBox, secondsLeft) {

    lockoutActive = true;

    let currentEndTime = parseInt(localStorage.getItem("lockoutEndTime") || "0", 10);
    if (currentEndTime < Date.now()) {
        localStorage.setItem("lockoutEndTime", Date.now() + secondsLeft * 1000);
    }

    let form = document.getElementById("id18");
    let fields = form.querySelectorAll("input, button");

    fields.forEach(function(field) {
        field.disabled = true;
    });

    let countdownTimer = setInterval(function() {

        errorBox.innerHTML = "Too many failed attempts. Try again in " + secondsLeft + " seconds.";
        secondsLeft--;

        if (secondsLeft < 0) {

            clearInterval(countdownTimer);

            fields.forEach(function(field) {
                field.disabled = false;
            });

            errorBox.classList.add("d-none");
            errorBox.innerHTML = "";
            failedAttempts = 0;
            localStorage.setItem("failedAttempts", failedAttempts);
            localStorage.removeItem("lockoutEndTime");
            lockoutActive = false;

        }

    }, 1000);

}
