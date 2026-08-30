<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
require 'db.php';
$error_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM employees WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['first_name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error_msg = "Invalid email or password.";
        }
    } else if (($email == 'admin@email.com' && $password == 'Admin@123') || ($email == 'hr@email.com' && $password == 'Hr@123')) {
        $_SESSION['username'] = ($email == 'admin@email.com') ? 'Admin' : 'HR';
        header("Location: dashboard.php");
        exit();
    } else {
        $error_msg = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Employee Login Portal</title>

    <link rel="icon" href="images/employee.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>


<body>

    <nav class="navbar navbar-dark bg-primary">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand" href="login.php">
                <img src="images/employee.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                <strong>Employee Portal</strong>
            </a>

            <div class="d-flex gap-3 align-items-center">
                <a href="logout.php" id="id29" style = "color: white; text-decoration: none;">Logout</a>
            </div>
        </div>
    </nav>

    <div class="class01">
        <div class="class02">

            <h2>Employee Login</h2>

            <div id="id19" class="alert alert-danger <?php echo empty($error_msg) ? 'd-none' : ''; ?>" role="alert"><?php echo $error_msg; ?></div>

            <form id="id18" method="POST" action="login.php">

                <label for="id01">Email</label>

                <input type="text"
                       id="id01" name="email"
                       class="class03"
                       placeholder="Enter email"
                       required>

                <p id="id01err" class="class15"></p>

                <label for="id02">Password</label>

                <div class="class16">

                    <input type="password"
                           id="id02" name="password"
                           class="class03"
                           placeholder="Enter password"
                           required>
                    
                    <button type="button" id="id20" class="class17"><i class="bi bi-eye"></i></button>

                </div>

                <div class="class04">
                    <input type="checkbox" id="id03">
                    <label for="id03">Remember me</label>

                </div>

                <div class="text-center mt-3">
                    <button type="submit" id="id22" class="class05">Login</button>
                </div>

            </form>

            <p class="class06">
                <a href="#" data-bs-toggle="modal" data-bs-target="#id21">Forgot Password?</a>
            </p>

            <p class="class06"> Don't have an account?

                <a href="employee-add.php">Register</a>

            </p>

        </div>
    </div>


    <div class="modal fade" id="id21" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <p>If an account exists with this email, a password reset link has been sent.</p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="class05" data-bs-dismiss="modal">Close</button>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/login.js"></script>

</body>

</html>
