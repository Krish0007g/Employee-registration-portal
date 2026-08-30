<?php
session_start();
require 'db.php';
$edit_mode = false;
$employee = [];
$auto_emp_id = "";

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM employees WHERE id=$id");
    if ($result) {
        $employee = mysqli_fetch_assoc($result);
    }
} else {
    $res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM employees");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $next_id = $row['cnt'] + 1;
        $auto_emp_id = "EMP" . str_pad($next_id, 3, "0", STR_PAD_LEFT);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $doj = mysqli_real_escape_string($conn, $_POST['doj']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $photo = $employee['photo'] ?? '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir);
        $target_file = $target_dir . time() . "_" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
<<<<<<< HEAD
        // Delete old photo file if it exists and a new one is being uploaded
        if (!empty($employee['photo']) && file_exists($employee['photo'])) {
            unlink($employee['photo']);
        }
        $photo = $target_file;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE employees SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', department='$department', designation='$designation', gender='$gender', doj='$doj', dob='$dob', salary='$salary', address='$address', password='$hashed_password', photo='$photo' WHERE id=$id";
=======
        $photo = $target_file;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE employees SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', department='$department', designation='$designation', gender='$gender', doj='$doj', dob='$dob', salary='$salary', address='$address', password='$password', photo='$photo' WHERE id=$id";
>>>>>>> 88dfad572db11cb612dd050be2a3221b8d22fe1f
        mysqli_query($conn, $sql);
    } else {
        $res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM employees");
        $row = mysqli_fetch_assoc($res);
        $next_id = $row['cnt'] + 1;
        $emp_id = "EMP" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

        $sql = "INSERT INTO employees (emp_id, first_name, last_name, email, phone, department, designation, gender, doj, dob, salary, address, password, photo) 
<<<<<<< HEAD
                VALUES ('$emp_id', '$first_name', '$last_name', '$email', '$phone', '$department', '$designation', '$gender', '$doj', '$dob', '$salary', '$address', '$hashed_password', '$photo')";
=======
                VALUES ('$emp_id', '$first_name', '$last_name', '$email', '$phone', '$department', '$designation', '$gender', '$doj', '$dob', '$salary', '$address', '$password', '$photo')";
>>>>>>> 88dfad572db11cb612dd050be2a3221b8d22fe1f
        mysqli_query($conn, $sql);
    }
    
    if (isset($_SESSION['username'])) {
        header("Location: employee-list.php");
    } else {
        header("Location: login.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee Registration portal</title>

    <link rel="icon" href="images/employee.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>


    <nav class="navbar navbar-dark bg-primary">

        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand" href="<?php echo isset($_SESSION['username']) ? 'dashboard.php' : 'login.php'; ?>" id="id33">
                <img src="images/employee.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                <strong>Employee Portal</strong>
            </a>

            <?php if (isset($_SESSION['username'])): ?>
            <div class="d-flex gap-3 align-items-center">
                <a href="logout.php" id="id29" style = "color: white; text-decoration: none;">Logout</a>
            </div>
            <?php endif; ?>

        </div>

    </nav>



    <div class="class08">

        <div class="class09">

            <h2 id="id32"><?php echo $edit_mode ? 'Edit Employee' : 'Employee Registration'; ?></h2>

            <div id="id31" class="alert alert-success d-none" role="alert"></div>

            <form id="id30" method="POST" action="employee-add.php" enctype="multipart/form-data" novalidate>
<input type="hidden" name="id" value="<?php echo $edit_mode ? $employee['id'] : ''; ?>">

                <div class="class10">

                    <div>

                        <label for="id04">First Name</label>

                        <input type="text"
                               id="id04" name="first_name" value="<?php echo $edit_mode ? htmlspecialchars($employee['first_name']) : ''; ?>"
                               class="class03"
                               placeholder="First name"
                               required>

                        <p id="id04err" class="class15"></p>

                    </div>


                    <div>

                        <label for="id05">Last Name</label>

                        <input type="text"
                               id="id05" name="last_name" value="<?php echo $edit_mode ? htmlspecialchars($employee['last_name']) : ''; ?>"
                               class="class03"
                               placeholder="Last name"
                               required>

                        <p id="id05err" class="class15"></p>

                    </div>

                </div>

                <div class="class10">

                    <div>

                        <label for="id06">Email</label>

                        <input type="email"
                               id="id06" name="email" value="<?php echo $edit_mode ? htmlspecialchars($employee['email']) : ''; ?>"
                               class="class03"
                               placeholder="Email"
                               required>

                        <p id="id06err" class="class15"></p>

                    </div>


                    <div>

                        <label for="id07">Phone Number</label>

                        <input type="tel"
                               id="id07" name="phone" value="<?php echo $edit_mode ? htmlspecialchars($employee['phone']) : ''; ?>"
                               class="class03"
                               placeholder="Phone number"
                               required>

                        <p id="id07err" class="class15"></p>

                    </div>

                </div>



                <div class="class10">

                    <div>

                        <label for="id08">Department</label>

                        <select id="id08" name="department" data-selected="<?php echo $edit_mode ? htmlspecialchars($employee['department']) : ''; ?>"
                                class="class03"
                                required>

                            <option value="">Select Department</option>

                        </select>

                        <p id="id08err" class="class15"></p>

                    </div>


                    <div>

                        <label>Gender</label>

                        <div class="class18">

                            <div class="class04">
                                <input type="radio" name="gender" id="id09Male" value="Male" <?php echo ($edit_mode && $employee['gender'] == 'Male') ? 'checked' : ''; ?>>
                                <label for="id09Male">Male</label>
                            </div>

                            <div class="class04">
                                <input type="radio" name="gender" id="id09Female" value="Female" <?php echo ($edit_mode && $employee['gender'] == 'Female') ? 'checked' : ''; ?>>
                                <label for="id09Female">Female</label>
                            </div>

                            <div class="class04">
                                <input type="radio" name="gender" id="id09Other" value="Other" <?php echo ($edit_mode && $employee['gender'] == 'Other') ? 'checked' : ''; ?>>
                                <label for="id09Other">Other</label>
                            </div>

                        </div>

                        <p id="id09err" class="class15"></p>

                    </div>

                </div>

                <div class="class10">

                    <div>

                        <label for="id23">Designation</label>

                        <select id="id23" name="designation" data-selected="<?php echo $edit_mode ? htmlspecialchars($employee['designation']) : ''; ?>"
                                class="class03"
                                required>
                            <option value="">Select Designation</option>
                        </select>

                        <p id="id23err" class="class15"></p>

                    </div>


                    <div>

                        <label for="id24">Date of Joining</label>

                        <input type="date"
                               id="id24" name="doj" value="<?php echo $edit_mode ? htmlspecialchars($employee['doj']) : ''; ?>"
                               class="class03"
                               required>

                        <p id="id24err" class="class15"></p>

                    </div>

                </div>

                <div class="class10">

                    <div>

                        <label for="id10">Date of Birth</label>

                        <input type="date"
                               id="id10" name="dob" value="<?php echo $edit_mode ? htmlspecialchars($employee['dob']) : ''; ?>"
                               class="class03"
                               required>

                        <p id="id10err" class="class15"></p>

                    </div>


                    <div>

                        <label for="id11">Employee ID</label>

                        <input type="text"
                               id="id11"
                               value="<?php echo $edit_mode ? htmlspecialchars($employee['emp_id']) : htmlspecialchars($auto_emp_id); ?>"
                               class="class03"
                               readonly>

                    </div>

                </div>

                <div class="class10">

                    <div>

                        <label for="id27">Salary</label>

                        <input type="text"
                               id="id27" name="salary" value="<?php echo $edit_mode ? htmlspecialchars($employee['salary']) : ''; ?>"
                               class="class03"
                               placeholder="Enter salary"
                               required>

                        <p id="id27err" class="class15"></p>

                    </div>


                    <div>

                        <label for="id25">Profile Photo</label>

                        <input type="file"
                               id="id25" name="photo"
                               class="class03"
                               accept="image/jpeg,image/png">

                        <img id="id26" class="class19" src="<?php echo $edit_mode ? htmlspecialchars($employee['photo']) : ''; ?>">

                        <p id="id25err" class="class15"></p>

                    </div>

                </div>



                <label for="id12">Address</label>

                <textarea id="id12" name="address"
                          class="class03"
                          rows="3"
                          placeholder="Enter address"
                          required><?php echo $edit_mode ? htmlspecialchars($employee['address']) : ''; ?></textarea>

                <p id="id12err" class="class15"></p>



                <div class="class10">

                    <div>

                        <label for="id13">Password</label>

                        <input type="password"
<<<<<<< HEAD
                               id="id13" name="password" value=""
=======
                               id="id13" name="password" value="<?php echo $edit_mode ? htmlspecialchars($employee['password']) : ''; ?>"
>>>>>>> 88dfad572db11cb612dd050be2a3221b8d22fe1f
                               class="class03"
                               placeholder="<?php echo $edit_mode ? 'Enter new password' : 'Enter password'; ?>"
                               required>

                        <p id="id13err" class="class15"></p>

                    </div>


                    <div>

                        <label for="id14">Confirm Password</label>

                        <input type="password"
<<<<<<< HEAD
                               id="id14" name="confirm_password" value=""
=======
                               id="id14" name="confirm_password" value="<?php echo $edit_mode ? htmlspecialchars($employee['password']) : ''; ?>"
>>>>>>> 88dfad572db11cb612dd050be2a3221b8d22fe1f
                               class="class03"
                               placeholder="<?php echo $edit_mode ? 'Confirm new password' : 'Confirm password'; ?>"
                               required>

                        <p id="id14err" class="class15"></p>

                    </div>

                </div>

                <p id="id16" class="class15"></p>

                <?php if (!$edit_mode): ?>
                <div class="class04">

                    <input type="checkbox"
                           id="id15"
                           required>

                    <label for="id15">I agree to the terms and conditions</label>

                </div>
                
                <p id="id15err" class="class15"></p>
                <?php endif; ?>

                <button type="submit" class="class05"><?php echo $edit_mode ? 'Update' : 'Register'; ?></button>
                <button type="reset"class="class11">Reset</button>

            </form>

        </div>

    </div>

    <script src="js/common.js"></script>
    <script>var isEditMode = <?php echo $edit_mode ? 'true' : 'false'; ?>;</script>
    <script src="js/employee-form.js"></script>

</body>

</html>
