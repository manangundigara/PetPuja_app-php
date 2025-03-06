<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'login') {
        
        // Check if both fields are empty
        if (empty($_POST['user_identifier']) && empty($_POST['user_password'])) {
            $response['message'] = "Both email/phone and password are required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Check individual fields
        if (empty($_POST['user_identifier'])) {
            $response['message'] = "Email or phone number is required.";
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

        $user_identifier = trim($_POST['user_identifier']);
        $user_password = trim($_POST['user_password']);

        // Check if input is a valid email or phone number
        if (filter_var($user_identifier, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT user_id, user_name, user_email, user_phone, user_password, is_delete FROM user_master WHERE user_email = ?";
        } elseif (preg_match("/^[0-9]{10}$/", $user_identifier)) {
            $query = "SELECT user_id, user_name, user_email, user_phone, user_password, is_delete FROM user_master WHERE user_phone = ?";
        } else {
            $response['message'] = "Invalid email or phone number format.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Execute the query using prepared statements
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $user_identifier);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if user exists
        if ($row = mysqli_fetch_assoc($result)) {
            // Check if the user is blocked
            if ($row['is_delete'] == 0) {
                $response['message'] = "Your account has been temporarily restricted. Please contact support for assistance.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // Verify the password with the stored hashed password
            if (password_verify($user_password, $row['user_password'])) {
                $response['status'] = 200;
                $response['message'] = "Login successful!";
                $response['user_data'] = [
                    'user_id' => $row['user_id'],
                    'user_name' => $row['user_name'],
                    'user_email' => $row['user_email'],
                    'user_phone' => $row['user_phone']
                ];
            } else {
                $response['message'] = "Incorrect password.";
                $response['status'] = 201;
            }
        } else {
            $response['message'] = "No user found with this email or phone number.";
            $response['status'] = 201;
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);

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
