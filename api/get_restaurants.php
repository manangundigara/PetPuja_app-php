<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'restaurants') {
        $query = "SELECT restaurant_id, restaurant_name, restaurant_address, restaurant_phone, restaurant_img FROM restaurant_master WHERE isDelete = 1 AND restaurant_status = 1 AND restaurant_request_status = 1";
        
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $restaurants = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $restaurants[] = $row;
                }
                http_response_code(200);
                $response["status"] = 200;
                $response["message"] = "Restaurants fetched successfully.";
                $response["data"] = $restaurants;
            } else {
                http_response_code(201);
                $response["status"] = 201;
                $response["message"] = "No restaurants found.";
            }
        } else {
            http_response_code(201);
            $response["status"] = 201;
            $response["message"] = "Database error: " . mysqli_error($conn);
        }
    } else {
        $response['status'] = 201;
        $response['message'] = "Invalid method token.";
    }
} else {
    http_response_code(201);
    $response["status"] = 201;
    $response["message"] = "Only POST method is allowed.";
}

echo json_encode($response);

?>
