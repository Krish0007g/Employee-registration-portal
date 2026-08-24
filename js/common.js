function isLoggedIn() {

    return sessionStorage.getItem("loggedIn") === "true";

}


function requireLogin() {
    if (!isLoggedIn() && !window.location.href.includes("login.html") && !window.location.href.includes("employee-add.html")) {
        window.location.href = "login.html";
    }
}


function logout() {

    sessionStorage.removeItem("loggedIn");
    sessionStorage.removeItem("username");
    window.location.href = "login.html";

}

function generateEmployeeId() {

    let employees = JSON.parse(localStorage.getItem("employees")) || [];
    let nextNumber = employees.length + 1;
    let paddedNumber = String(nextNumber).padStart(3, "0");
    return "EMP" + paddedNumber;

}

function calculateAge(dob) {

    let birthDate = new Date(dob);
    let today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    let monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {

        age--;

    }

    return age;

}

function formatSalaryWithCommas(rawValue) {

    let numberValue = rawValue.replace(/[^0-9]/g, "");

    if (numberValue === "") {

        return "";

    }

    return Number(numberValue).toLocaleString("en-IN");

}

document.addEventListener("DOMContentLoaded", function() {
    if (isLoggedIn()) {
        document.querySelectorAll('a[href="login.html"], a[href="employee-add.html"]').forEach(function(link) {
            if (link.closest('nav')) link.style.display = "none";
        });
    } else {
        document.querySelectorAll('a[href="employee-list.html"]').forEach(function(link) {
            if (link.closest('nav')) link.style.display = "none";
        });
    }

    let logoutBtn = document.getElementById("id29");
    if (logoutBtn) {
        if (!isLoggedIn()) {
            logoutBtn.style.display = "none";
        }
        logoutBtn.addEventListener("click", function(event) {
            event.preventDefault();
            logout();
        });
    }
    requireLogin();

    let portalLink = document.getElementById("id33");
    if (portalLink) {
        if (isLoggedIn()) {
            portalLink.href = "dashboard.html";
        } else {
            portalLink.href = "login.html";
        }
    }

    let welcomeText = document.getElementById("id28");
    if (welcomeText) {
        welcomeText.innerHTML = "Hi, " + (sessionStorage.getItem("username") || "");
    }

    let empTable = document.getElementById("id17");
    if (empTable) {
        showEmployees();
    }
});

function showEmployees() {
    let employees = JSON.parse(localStorage.getItem("employees")) || [];
    let table = document.getElementById("id17");
    if (!table) return;
    table.innerHTML = "";
    employees.forEach(function(employee, index) {
        let row = document.createElement("tr");

        let cell01 = document.createElement("td");
        cell01.innerHTML = employee.id;

        let cell02 = document.createElement("td");
        cell02.innerHTML = employee.name;

        let cell03 = document.createElement("td");
        cell03.innerHTML = employee.email;

        let cell04 = document.createElement("td");
        cell04.innerHTML = employee.phone;

        let cell05 = document.createElement("td");
        cell05.innerHTML = employee.department;

        let cell06 = document.createElement("td");
        cell06.innerHTML = employee.designation || "";

        let cell07 = document.createElement("td");
        cell07.innerHTML = employee.gender;

        let cell08 = document.createElement("td");
        cell08.innerHTML = employee.doj || "";

        let cell09 = document.createElement("td");
        cell09.innerHTML = employee.salary || "";

        let cell10 = document.createElement("td");
        if (employee.photo) {
            cell10.innerHTML = "<img src='" + employee.photo + "' class='class19'>";
        }

        let cell11 = document.createElement("td");

        let editButton = document.createElement("button");
        editButton.innerHTML = "Edit";
        editButton.className = "class05 class22";
        editButton.addEventListener("click", function() {
            window.location.href = "employee-add.html?edit=" + index;
        });

        let deleteButton = document.createElement("button");
        deleteButton.innerHTML = "Delete";
        deleteButton.className = "class11 class22";
        deleteButton.addEventListener("click", function() {
            deleteEmployee(index);
        });

        cell11.appendChild(editButton);
        cell11.appendChild(deleteButton);

        row.appendChild(cell01);
        row.appendChild(cell02);
        row.appendChild(cell03);
        row.appendChild(cell04);
        row.appendChild(cell05);
        row.appendChild(cell06);
        row.appendChild(cell07);
        row.appendChild(cell08);
        row.appendChild(cell09);
        row.appendChild(cell10);
        row.appendChild(cell11);
        table.appendChild(row);
    });
}

let employeeToDeleteIndex = null;
let deleteModalInstance;

function deleteEmployee(index) {
    employeeToDeleteIndex = index;
    if (!deleteModalInstance) {
        let el = document.getElementById('deleteModal');
        if (el) {
            deleteModalInstance = new bootstrap.Modal(el);
        }
    }
    if (deleteModalInstance) {
        deleteModalInstance.show();
    } else {
        // Fallback if modal is not in DOM
        performDelete(index);
    }
}

function performDelete(index) {
    let employees = JSON.parse(localStorage.getItem("employees")) || [];
    employees.splice(index, 1);
    localStorage.setItem("employees", JSON.stringify(employees));
    showEmployees();
}

document.addEventListener("DOMContentLoaded", function() {
    let confirmBtn = document.getElementById("confirmDeleteBtn");
    if (confirmBtn) {
        confirmBtn.addEventListener("click", function() {
            if (employeeToDeleteIndex !== null) {
                performDelete(employeeToDeleteIndex);
                if (deleteModalInstance) {
                    deleteModalInstance.hide();
                }
                employeeToDeleteIndex = null;
            }
        });
    }
});
