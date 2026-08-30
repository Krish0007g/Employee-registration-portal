function formatSalaryWithCommas(rawValue) {
    let numberValue = rawValue.replace(/[^0-9]/g, "");
    if (numberValue === "") {
        return "";
    }
    return Number(numberValue).toLocaleString("en-IN");
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
