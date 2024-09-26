<?php
// Include Firebase JWT library
require 'vendor/autoload.php'; // Make sure this path is correct
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Database connection
$con = mysqli_connect("localhost", "root", "", "LOGINEMP");

// Secret key for JWT encoding/decoding
$secret_key = "YOUR_SECRET_KEY";

// Initialize response array
$response = array();

if ($con) {
    // Check if all required fields are present
    if (isset($_POST['employee_name'], $_POST['employee_id'], $_POST['password'])) {
        // Sanitize the inputs to prevent SQL injection
        $employee_name = mysqli_real_escape_string($con, $_POST['employee_name']);
        $employee_id = mysqli_real_escape_string($con, $_POST['employee_id']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        // Make sure the fields are not empty
        if (!empty($employee_name) && !empty($employee_id) && !empty($password)) {
            // Query to insert the employee data
            $sql = "INSERT INTO login (employee_name, employee_id, password) VALUES ('$employee_name', '$employee_id', '$password')";
            $result = mysqli_query($con, $sql);

            if ($result) {
                // Return success response if data is inserted successfully
                $response = array(
                    "status" => "success",
                    "message" => "Employee data inserted successfully."
                );
            } else {
                // Return error response if query execution fails
                $response = array(
                    "status" => "error",
                    "message" => "Failed to insert employee data: " . mysqli_error($con)
                );
            }
        } else {
            // Return error response if any field is empty
            $response = array(
                "status" => "error",
                "message" => "Employee name, ID, and password must not be empty."
            );
        }
    } else {
        // Return error response if fields are missing
        $response = array(
            "status" => "error",
            "message" => "Employee name, ID, and password must be provided."
        );
    }
} else {
    // Return error response if database connection fails
    $response = array(
        "status" => "error",
        "message" => "Database connection failed: " . mysqli_connect_error()
    );
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
