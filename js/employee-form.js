let departmentList = ["IT", "HR", "Finance", "Marketing"];
let designationMap = {
    "IT": ["Software Engineer", "Frontend Developer", "Backend Developer", "QA Analyst"],
    "HR": ["HR Manager", "Recruiter", "HR Coordinator"],
    "Finance": ["Financial Analyst", "Accountant", "Finance Manager"],
    "Marketing": ["Marketing Executive", "Content Writer", "SEO Specialist"]
};
let editIndex = null;
let photoDataUrl = "";

document.addEventListener("DOMContentLoaded", function() {

    populateDepartments();
    document.getElementById("id08").addEventListener("change", function() {
        populateDesignations(this.value);
    });
    setupEditMode();
    attachValidationListeners();
    attachPhotoListener();
    attachSalaryFormatting();

    document.getElementById("id30").addEventListener("submit", handleSubmit);
    document.getElementById("id30").addEventListener("reset", handleReset);

});

function populateDepartments() {

    let select = document.getElementById("id08");

    departmentList.forEach(function(department) {

        let option = document.createElement("option");
        option.value = department;
        option.innerHTML = department;
        select.appendChild(option);

    });

}

function populateDesignations(department) {

    let select = document.getElementById("id23");
    select.innerHTML = '<option value="">Select Designation</option>';
    
    if (!department || !designationMap[department]) return;

    designationMap[department].forEach(function(designation) {

        let option = document.createElement("option");
        option.value = designation;
        option.innerHTML = designation;
        select.appendChild(option);

    });

}

function setupEditMode() {

    let params = new URLSearchParams(window.location.search);
    let editParam = params.get("edit");

    if (editParam === null) {

        document.getElementById("id11").value = generateEmployeeId();
        return;

    }

    editIndex = Number(editParam);

    let employees = JSON.parse(localStorage.getItem("employees")) || [];
    let employee = employees[editIndex];

    if (!employee) {

        document.getElementById("id11").value = generateEmployeeId();
        return;

    }

    let nameParts = employee.name.split(" ");

    document.getElementById("id04").value = nameParts[0] || "";
    document.getElementById("id05").value = nameParts.slice(1).join(" ") || "";
    document.getElementById("id06").value = employee.email;
    document.getElementById("id07").value = employee.phone;
    document.getElementById("id08").value = employee.department;
    populateDesignations(employee.department);
    document.getElementById("id23").value = employee.designation || "";
    document.getElementById("id24").value = employee.doj || "";
    document.getElementById("id10").value = employee.dob || "";
    document.getElementById("id11").value = employee.id;
    document.getElementById("id27").value = employee.salary || "";
    document.getElementById("id12").value = employee.address || "";
    document.getElementById("id13").value = employee.password || "";
    document.getElementById("id14").value = employee.password || "";

    if (employee.gender) {

        let genderInput = document.getElementById("id09" + employee.gender);

        if (genderInput) {

            genderInput.checked = true;

        }

    }

    if (employee.photo) {

        photoDataUrl = employee.photo;
        let preview = document.getElementById("id26");
        preview.src = employee.photo;

    }

    document.getElementById("id32").innerHTML = "Edit Employee";
    document.querySelector("#id30 button[type='submit']").innerHTML = "Update";

}

function setFieldValid(input, errorEl) {

    input.classList.remove("class21");
    input.classList.add("class20");
    errorEl.innerHTML = "";

}

function setFieldInvalid(input, errorEl, message) {

    input.classList.remove("class20");
    input.classList.add("class21");
    errorEl.innerHTML = message;

}

function validateFirstName() {

    let input = document.getElementById("id04");
    let errorEl = document.getElementById("id04err");
    let regex = /^[A-Za-z ]{3,}$/;

    if (!regex.test(input.value.trim())) {

        setFieldInvalid(input, errorEl, "Enter at least 3 letters, letters and spaces only.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateLastName() {

    let input = document.getElementById("id05");
    let errorEl = document.getElementById("id05err");
    let regex = /^[A-Za-z ]{3,}$/;

    if (!regex.test(input.value.trim())) {

        setFieldInvalid(input, errorEl, "Enter at least 3 letters, letters and spaces only.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateEmail() {

    let input = document.getElementById("id06");
    let errorEl = document.getElementById("id06err");
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let value = input.value.trim();

    if (!regex.test(value)) {

        setFieldInvalid(input, errorEl, "Enter a valid email address.");
        return false;

    }

    let employees = JSON.parse(localStorage.getItem("employees")) || [];

    let duplicate = employees.some(function(employee, index) {

        return employee.email.toLowerCase() === value.toLowerCase() && index !== editIndex;

    });

    if (duplicate) {

        setFieldInvalid(input, errorEl, "This email is already registered.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validatePhone() {

    let input = document.getElementById("id07");
    let errorEl = document.getElementById("id07err");
    let regex = /^[6-9]\d{9}$/;

    if (!regex.test(input.value.trim())) {

        setFieldInvalid(input, errorEl, "Enter a valid 10 digit mobile number starting with 6-9.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateDepartment() {

    let input = document.getElementById("id08");
    let errorEl = document.getElementById("id08err");

    if (input.value === "") {

        setFieldInvalid(input, errorEl, "Please select a department.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateGender() {

    let errorEl = document.getElementById("id09err");
    let checked = document.querySelector("input[name='gender']:checked");

    if (!checked) {

        errorEl.innerHTML = "Please select a gender.";
        return false;

    }

    errorEl.innerHTML = "";
    return true;

}

function validateDesignation() {

    let input = document.getElementById("id23");
    let errorEl = document.getElementById("id23err");

    if (input.value === "") {

        setFieldInvalid(input, errorEl, "Please select a designation.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateDoj() {

    let input = document.getElementById("id24");
    let errorEl = document.getElementById("id24err");

    if (input.value === "") {

        setFieldInvalid(input, errorEl, "Date of joining is required.");
        return false;

    }

    let today = new Date();
    today.setHours(0, 0, 0, 0);

    if (new Date(input.value) > today) {

        setFieldInvalid(input, errorEl, "Date of joining cannot be in the future.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateDob() {

    let input = document.getElementById("id10");
    let errorEl = document.getElementById("id10err");

    if (input.value === "") {

        setFieldInvalid(input, errorEl, "Date of birth is required.");
        return false;

    }

    if (calculateAge(input.value) < 18) {

        setFieldInvalid(input, errorEl, "Employee must be at least 18 years old.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateSalary() {

    let input = document.getElementById("id27");
    let errorEl = document.getElementById("id27err");
    let numericValue = Number(input.value.replace(/,/g, ""));

    if (input.value.trim() === "" || isNaN(numericValue) || numericValue <= 0) {

        setFieldInvalid(input, errorEl, "Enter a valid positive salary amount.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateAddress() {

    let input = document.getElementById("id12");
    let errorEl = document.getElementById("id12err");

    if (input.value.trim().length < 10) {

        setFieldInvalid(input, errorEl, "Enter at least 10 characters.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validatePassword() {

    let input = document.getElementById("id13");
    let errorEl = document.getElementById("id13err");

    if (input.value.length < 6) {

        setFieldInvalid(input, errorEl, "Password must be at least 6 characters.");
        return false;

    }

    setFieldValid(input, errorEl);
    return true;

}

function validateConfirmPassword() {

    let password = document.getElementById("id13").value;
    let input = document.getElementById("id14");
    let errorEl = document.getElementById("id14err");
    let mismatchEl = document.getElementById("id16");

    if (input.value !== password) {

        setFieldInvalid(input, errorEl, "Passwords do not match.");
        mismatchEl.innerHTML = "Password and Confirm Password do not match!";
        return false;

    }

    setFieldValid(input, errorEl);
    mismatchEl.innerHTML = "";
    return true;

}

function validateTerms() {
    let input = document.getElementById("id15");
    let errorEl = document.getElementById("id15err");
    if (!input.checked) {
        setFieldInvalid(input, errorEl, "Please agree to the terms and conditions before submitting.");
        return false;
    }
    setFieldValid(input, errorEl);
    return true;
}

function attachValidationListeners() {

    document.getElementById("id04").addEventListener("blur", validateFirstName);
    document.getElementById("id05").addEventListener("blur", validateLastName);
    document.getElementById("id06").addEventListener("blur", validateEmail);
    document.getElementById("id07").addEventListener("blur", validatePhone);
    document.getElementById("id08").addEventListener("blur", validateDepartment);
    document.querySelectorAll("input[name='gender']").forEach(function(radio) {

        radio.addEventListener("change", validateGender);

    });
    document.getElementById("id23").addEventListener("blur", validateDesignation);
    document.getElementById("id24").addEventListener("blur", validateDoj);
    document.getElementById("id10").addEventListener("blur", validateDob);
    document.getElementById("id27").addEventListener("blur", validateSalary);
    document.getElementById("id12").addEventListener("blur", validateAddress);
    document.getElementById("id13").addEventListener("blur", validatePassword);
    document.getElementById("id14").addEventListener("blur", validateConfirmPassword);
    document.getElementById("id15").addEventListener("change", validateTerms);

}

function attachSalaryFormatting() {

    document.getElementById("id27").addEventListener("blur", function() {

        this.value = formatSalaryWithCommas(this.value);

    });

}

function attachPhotoListener() {

    document.getElementById("id25").addEventListener("change", function(event) {

        let errorEl = document.getElementById("id25err");
        let file = event.target.files[0];
        let preview = document.getElementById("id26");

        if (!file) {

            return;

        }

        let allowedTypes = ["image/jpeg", "image/png"];

        if (!allowedTypes.includes(file.type)) {

            errorEl.innerHTML = "Only JPG or PNG images are allowed.";
            event.target.value = "";
            return;

        }

        if (file.size > 2 * 1024 * 1024) {

            errorEl.innerHTML = "Image size must not exceed 2 MB.";
            event.target.value = "";
            return;

        }

        errorEl.innerHTML = "";

        let reader = new FileReader();

        reader.onload = function(readerEvent) {

            photoDataUrl = readerEvent.target.result;
            preview.src = photoDataUrl;

        };

        reader.readAsDataURL(file);

    });

}

function handleSubmit(event) {

    event.preventDefault();

    let termsValid = validateTerms();

    let validations = [

        validateFirstName(),
        validateLastName(),
        validateEmail(),
        validatePhone(),
        validateDepartment(),
        validateGender(),
        validateDesignation(),
        validateDoj(),
        validateDob(),
        validateSalary(),
        validateAddress(),
        validatePassword(),
        validateConfirmPassword(),
        termsValid
    ];

    let allValid = validations.every(function(result) {

        return result === true;

    });

    if (!allValid) {

        let firstInvalid = document.querySelector(".class21");

        if (firstInvalid) {

            firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });

        } else {

            document.querySelector("#id30 .class15:not(:empty)").scrollIntoView({ behavior: "smooth", block: "center" });

        }

        return;

    }

    let firstName = document.getElementById("id04").value.trim();
    let lastName = document.getElementById("id05").value.trim();
    let genderInput = document.querySelector("input[name='gender']:checked");

    let employee = {

        id: document.getElementById("id11").value,
        name: firstName + " " + lastName,
        email: document.getElementById("id06").value.trim(),
        phone: document.getElementById("id07").value.trim(),
        department: document.getElementById("id08").value,
        designation: document.getElementById("id23").value.trim(),
        gender: genderInput.value,
        doj: document.getElementById("id24").value,
        dob: document.getElementById("id10").value,
        salary: document.getElementById("id27").value,
        address: document.getElementById("id12").value.trim(),
        password: document.getElementById("id13").value,
        photo: photoDataUrl

    };

    let employees = JSON.parse(localStorage.getItem("employees")) || [];

    if (editIndex !== null) {

        employees[editIndex] = employee;

    } else {

        employees.push(employee);

    }

    localStorage.setItem("employees", JSON.stringify(employees));

    let successBox = document.getElementById("id31");
    successBox.classList.remove("d-none");
    successBox.innerHTML = editIndex !== null ? "Employee updated successfully." : "Employee registered successfully.";

    setTimeout(function() {

        window.location.href = "employee-list.html";

    }, 1200);

}

function handleReset() {

    setTimeout(function() {

        document.querySelectorAll("#id30 .class03").forEach(function(field) {

            field.classList.remove("class20");
            field.classList.remove("class21");

        });

        document.querySelectorAll("#id30 .class15").forEach(function(errorEl) {

            errorEl.innerHTML = "";

        });

        document.getElementById("id16").innerHTML = "";
        document.getElementById("id26").removeAttribute("src");
        photoDataUrl = "";

        if (editIndex === null) {

            document.getElementById("id11").value = generateEmployeeId();

        }

    }, 0);

}
