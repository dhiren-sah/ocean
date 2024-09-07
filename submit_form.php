<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "origin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve and sanitize form data
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $passcode = password_hash(trim($_POST['passcode']), PASSWORD_BCRYPT);
    $gender = $conn->real_escape_string(trim($_POST['gender']));

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, passcode, gender) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $passcode, $gender);

    // Execute the statement
    if ($stmt->execute()) {
        echo "ID created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>
