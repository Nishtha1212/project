<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acelt";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User Registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'student')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// User Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            echo "Login successful!";
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}

// Course Enrollment
if (isset($_POST['enroll'])) {
    $user_id = $_POST['user_id'];
    $course_id = $_POST['course_id'];
    
    $sql = "INSERT INTO enrollments (user_id, course_id) VALUES ('$user_id', '$course_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Enrolled successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Admin: Add Course
if (isset($_POST['add_course']) && $_SESSION['role'] == 'admin') {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    
    $sql = "INSERT INTO courses (name, description) VALUES ('$course_name', '$description')";
    if ($conn->query($sql) === TRUE) {
        echo "Course added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Progress Tracking
if (isset($_POST['update_progress'])) {
    $user_id = $_POST['user_id'];
    $course_id = $_POST['course_id'];
    $progress = $_POST['progress'];
    
    $sql = "UPDATE enrollments SET progress='$progress' WHERE user_id='$user_id' AND course_id='$course_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Progress updated!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
