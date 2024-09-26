<?php
// Include Firebase JWT library
require 'vendor/autoload.php'; // Ensure this is the correct path
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Database connection
$con = mysqli_connect("localhost", "root", "", "LOGINEMP");

// Secret key for JWT encoding/decoding
$secret_key = "YOUR_SECRET_KEY";  // Replace with a secret key

// Initialize response array
$response = array();

if ($con) {
    // Check if employee_name and password are provided
    if (isset($_POST['employee_name'], $_POST['password'])) {
        // Sanitize input data to prevent SQL injection
        $employee_name = mysqli_real_escape_string($con, $_POST['employee_name']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        // Check if fields are not empty
        if (!empty($employee_name) && !empty($password)) {
            // Query to check if the provided credentials are correct
            $sql = "SELECT * FROM login WHERE employee_name = '$employee_name' AND password = '$password'";
            $result = mysqli_query($con, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch employee data
                $row = mysqli_fetch_assoc($result);
                $employee_id = $row['employee_id'];  // Get the employee ID

                // Generate JWT token if credentials are valid
                $payload = array(
                    "iss" => "http://your-domain.com",  // Issuer
                    "aud" => "http://your-domain.com",  // Audience
                    "iat" => time(),                    // Issued at
                    "nbf" => time(),                    // Not before
                    "exp" => time() + 3600,             // Token expires in 1 hour
                    "data" => array(
                        "employee_id" => $employee_id,
                        "employee_name" => $employee_name
                    )
                );

                // Encode JWT token
                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                // Return success response with JWT token
                $response = array(
                    "status" => "success",
                    "message" => "Login successful",
                    "jwt" => $jwt
                );
            } else {
                // Invalid credentials
                $response = array(
                    "status" => "error",
                    "message" => "Invalid employee name or password"
                );
            }
        } else {
            // If fields are empty
            $response = array(
                "status" => "error",
                "message" => "Employee name and password must not be empty"
            );
        }
    } else {
        // If fields are missing
        $response = array(
            "status" => "error",
            "message" => "Employee name and password must be provided"
        );
    }
} else {
    // If database connection fails
    $response = array(
        "status" => "error",
        "message" => "Database connection failed: " . mysqli_connect_error()
    );
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
