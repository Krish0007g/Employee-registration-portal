<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee portal Dashboard</title>

    <link rel="icon" href="images/employee.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>


<body style="overflow: hidden;">


    <nav class="navbar navbar-dark bg-primary">

        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand" href="dashboard.php">
                <img src="images/employee.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                <strong>Employee Portal</strong>
            </a>


            <div class="d-flex gap-3 align-items-center">
                <span id="id28" style="color: white; margin-right: 15px;">Hi, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                <a href="logout.php" id="id29" style = "color: white; text-decoration: none;">Logout</a>
            </div>

        </div>

    </nav>


    <div class="class12">

        <div class="class13 text-center">

            <img src="images/dashboard image.png" alt="Dashboard Image" class="img-fluid mb-4" style="max-height: 300px;">
            <h2>Welcome</h2>
            
            <a href="employee-list.php" class="class05" style="text-decoration: none;">View Employees</a>
            <a href="employee-add.php" class="class11" style="text-decoration: none; margin-left: 10px;">Add Employee</a>

        </div>

    </div>


    <script src="js/common.js"></script>

    

</body>

</html>
