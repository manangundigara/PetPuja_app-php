<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'change_password') {

        // Initialize an errors array
        $errors = [];

        // Validate required fields
        if (empty($_POST['user_id'])) {
            $errors[] = "Email is required.";
        }
        if (empty($_POST['old_password'])) {
            $errors[] = "Old password is required.";
        }
        if (empty($_POST['new_password'])) {
            $errors[] = "New password is required.";
        }
        if (empty($_POST['confirm_password'])) {
            $errors[] = "Confirm password is required.";
        }

        // If there are errors, return them
        if (!empty($errors)) {
            $response['message'] = $errors;
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        $user_id = trim($_POST['user_id']);
        $old_password = trim($_POST['old_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Check if email exists
        $query = "SELECT user_id, user_password FROM user_master WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $hashed_old_password = $row['user_password'];

            // Verify old password
            if (!password_verify($old_password, $hashed_old_password)) {
                $response['message'] = "Old password is incorrect.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // Check if new password and confirm password match
            if ($new_password !== $confirm_password) {
                $response['message'] = "New password and confirm password do not match.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // Hash new password
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update new password in the database
            $updateQuery = "UPDATE user_master SET user_password = ? WHERE user_id = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ss", $hashed_new_password, $user_id);
            if (mysqli_stmt_execute($updateStmt)) {
                $response['message'] = "Password changed successfully!";
                $response['status'] = 200;
            } else {
                $response['message'] = "Error updating password.";
                $response['status'] = 201;
            }

            // Close statement
            mysqli_stmt_close($updateStmt);

        } else {
            $response['message'] = "Email not found.";
            $response['status'] = 201;
        }

        // Close statement
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
