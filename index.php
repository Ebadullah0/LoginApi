<?php
// Include Firebase JWT library
require 'vendor/autoload.php'; // Ensure this path is correct
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Database connection
$con = mysqli_connect("localhost", "root", "", "LOGINEMP");

// Secret key for JWT encoding/decoding
$secret_key = "YOUR_SECRET_KEY";

// Initialize response array
$response = array();

// Debug: Check database connection
if (!$con) {
    $response = array(
        "status" => "error",
        "message" => "Database connection failed: " . mysqli_connect_error()
    );
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

// Debug: Log all POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(print_r($_POST, true)); // Log POST data for debugging

    // Retrieve POST data
    $employee_name = $_POST['employee_name'] ?? null;
    $employee_id = $_POST['employee_id'] ?? null;
    $password = $_POST['password'] ?? null;

    // Check if all fields are provided
    if (empty($employee_name) || empty($employee_id) || empty($password)) {
        $response = array(
            "status" => "error",
            "message" => "Employee name, ID, and password must be provided."
        );
        header("Content-Type: application/json");
        echo json_encode($response);
        exit;
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO `login` (`employee_name`, `employee_id`, `password`) VALUES (?, ?, ?)");

    if ($stmt) {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Bind parameters (s = string, i = integer)
        $stmt->bind_param("sis", $employee_name, $employee_id, $hashed_password);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Successful insertion
            $response = array(
                "status" => "success",
                "message" => "Employee data inserted successfully."
            );
        } else {
            // Insertion failed
            $response = array(
                "status" => "error",
                "message" => "Error inserting data: " . $stmt->error
            );
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // If preparing the statement fails
        $response = array(
            "status" => "error",
            "message" => "Error preparing statement: " . $con->error
        );
    }
} else {
    $response = array(
        "status" => "error",
        "message" => "Invalid request method."
    );
}

// Set header for JSON response
header("Content-Type: application/json");
echo json_encode($response);

// Close the database connection
mysqli_close($con);
?>
