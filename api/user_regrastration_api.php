<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'register') {
        // Check if all fields are empty
        if (
            empty($_POST['user_name']) &&
            empty($_POST['user_email']) &&
            empty($_POST['user_phone']) &&
            empty($_POST['user_password'])
        ) {
            $response['message'] = "All fields are required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Validate each field and return specific error messages
        if (empty($_POST['user_name'])) {
            $response['message'] = "User name is required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }
        if (empty($_POST['user_email'])) {
            $response['message'] = "Email is required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }
        if (empty($_POST['user_phone'])) {
            $response['message'] = "Phone number is required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }
        if (empty($_POST['user_password'])) {
            $response['message'] = "Password is required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        $user_name = trim($_POST['user_name']);
        $user_email = trim($_POST['user_email']);
        $user_phone = trim($_POST['user_phone']);
        $user_password = trim($_POST['user_password']);
        $is_delete = 1;
        $create_date = date('Y-m-d H:i:s');
        $update_by = NULL;
        $updated_date = NULL;

        // Name validation
        if (!preg_match("/^[a-zA-Z ]+$/", $user_name) || strlen($user_name) < 3) {
            $response['message'] = "User name must be at least 3 characters long and contain only letters and spaces.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Email validation
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = "Invalid email format.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Phone number validation
        if (!preg_match("/^[0-9]{10}$/", $user_phone)) {
            $response['message'] = "Phone number must contain exactly 10 digits.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Password validation
        if (!preg_match("/^[a-zA-Z0-9#@!$%^&*]{8,30}$/", $user_password)) {
            $response['message'] = "Password must be between 8 and 30 characters long and contain only alphanumeric characters or allowed symbols (#, @, !, $, %, ^, &, *).";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Hash password
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Check if email or phone number already exists
        $check_query = "SELECT * FROM user_master WHERE user_email = '$user_email' OR user_phone = '$user_phone'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $response['message'] = "Email or phone number already exists.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Insert user into database
        $insert_query = "INSERT INTO user_master (user_name, user_email, user_phone, user_password, is_delete, create_date, updated_date) 
                        VALUES ('$user_name', '$user_email', '$user_phone', '$hashed_password', '$is_delete', '$create_date', '$updated_date')";
        
        if (mysqli_query($conn, $insert_query)) {
            $response['status'] = 200;
            $response['message'] = "User registered successfully!";
        } else {
            $response['status'] = 201;
            $response['message'] = "Database error: " . mysqli_error($conn);
        }
    } else {
        $response['message'] = "Invalid method token.";
        $response['status'] = 201;
    }
} else {
    $response['message'] = "Only POST method is allowed.";
    $response['status'] = 201;
}

echo json_encode($response);

?>
