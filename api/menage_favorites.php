<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method'])) {
        $method = $_POST['method'];
        $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
        $menu_id = isset($_POST['menu_id']) ? trim($_POST['menu_id']) : '';

        if (empty($user_id) || empty($menu_id)) {
            $response["status"] = 201;
            $response["message"] = "User ID and Menu ID are required.";
            echo json_encode($response);
            exit;
        }

        if ($method === 'favorites') {
            $query = "SELECT favorite_id FROM favorite_master WHERE user_id = '$user_id' AND menu_id = '$menu_id'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                // Delete from favorites
                $delete_query = "DELETE FROM favorite_master WHERE user_id = '$user_id' AND menu_id = '$menu_id'";
                if (mysqli_query($conn, $delete_query)) {
                    http_response_code(200);
                    $response["status"] = 200;
                    $response["message"] = "Menu item removed from favorites.";
                } else {
                    http_response_code(201);
                    $response["status"] = 201;
                    $response["message"] = "Database error: " . mysqli_error($conn);
                }
            } else {
                // Add to favorites
                $insert_query = "INSERT INTO favorite_master (user_id, menu_id, favorite_createdAt) VALUES ('$user_id', '$menu_id', NOW())";
                if (mysqli_query($conn, $insert_query)) {
                    http_response_code(200);
                    $response["status"] = 200;
                    $response["message"] = "Menu item added to favorites.";
                } else {
                    http_response_code(201);
                    $response["status"] = 201;
                    $response["message"] = "Database error: " . mysqli_error($conn);
                }
            }
        } else {
            $response['status'] = 201;
            $response['message'] = "Invalid method token.";
        }
    } else {
        $response['status'] = 201;
        $response['message'] = "Method is required.";
    }
} else {
    http_response_code(201);
    $response["status"] = 201;
    $response["message"] = "Only POST method is allowed.";
}

echo json_encode($response);

?>
