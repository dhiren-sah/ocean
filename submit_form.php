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

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize form inputs
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $passcode = trim($_POST['passcode']);
    $gender = $conn->real_escape_string(trim($_POST['gender']));

    // Check if the email is in a valid format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Hash the password
    $hashed_passcode = password_hash($passcode, PASSWORD_BCRYPT);

    // Prepare and bind the SQL query
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, passcode, gender) VALUES (?, ?, ?, ?, ?)");
    
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters (s = string)
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_passcode, $gender);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "ID created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
