<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "employee_portal";

$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");
mysqli_select_db($conn, $dbname);

$tableQuery = "CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id VARCHAR(50),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    department VARCHAR(50),
    designation VARCHAR(50),
    gender VARCHAR(20),
    doj DATE,
    dob DATE,
    salary VARCHAR(50),
    address TEXT,
    password VARCHAR(255),
    photo VARCHAR(255)
)";
mysqli_query($conn, $tableQuery);
?>