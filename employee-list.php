<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");
    header("Location: employee-list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee list</title>

    <link rel="icon" href="images/employee.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

</head>


<body>


    <nav class="navbar navbar-dark bg-primary">

        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand" href="dashboard.php">
                <img src="images/employee.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                <strong>Employee Portal</strong>
            </a>


            <div class="d-flex gap-3 align-items-center">
                <a href="logout.php" id="id29" style = "color: white; text-decoration: none;">Logout</a>
            </div>

        </div>

    </nav>



    <div class="class12">

        <div class="class13">
            <div class="class14">

                <h2>Employee List</h2>
                <a href="employee-add.php" class="class05">Add Employee</a>

            </div>



            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Gender</th>
                            <th>Date of Joining</th>
                            <th>Salary</th>
                            <th>Photo</th>
                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody id="id17">
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM employees");
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['emp_id'] . "</td>";
                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['phone'] . "</td>";
                            echo "<td>" . $row['department'] . "</td>";
                            echo "<td>" . $row['designation'] . "</td>";
                            echo "<td>" . $row['gender'] . "</td>";
                            echo "<td>" . $row['doj'] . "</td>";
                            echo "<td>" . $row['salary'] . "</td>";
                            echo "<td>";
                            if ($row['photo']) {
                                echo "<img src='" . $row['photo'] . "' class='class19'>";
                            }
                            echo "</td>";
                            echo "<td>
                                    <a href='employee-add.php?edit=" . $row['id'] . "' class='class05 class22' style='text-decoration:none;'>Edit</a>
                                    <button type='button' class='class11 class22' style='text-decoration:none; border:none;' onclick='showDeleteModal(" . $row['id'] . ")'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this employee?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="class05" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="class11" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/common.js"></script>
    <script>
        let deleteId = null;
        function showDeleteModal(id) {
            deleteId = id;
            let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteId) {
                window.location.href = 'employee-list.php?delete=' + deleteId;
            }
        });
    </script>
</body>

</html>
