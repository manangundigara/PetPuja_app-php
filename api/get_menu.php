<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'menu') {
        if (empty($_POST['category_id'])) {
            $response['message'] = "Category ID is required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        $category_id = intval($_POST['category_id']);
        $menu_type = isset($_POST['menu_type']) ? trim($_POST['menu_type']) : "";

        $query = "SELECT 
                    m.menu_id, m.menu_name, 
                    m.menu_description, m.menu_price, m.menu_img, m.manu_type,
                    c.category_name, c.category_img,
                    r.restaurant_name, r.restaurant_owner_name, r.restaurant_description, 
                    r.restaurant_address, r.restaurant_phone, r.restaurant_img
                  FROM menu_master m
                  LEFT JOIN category_master c ON m.category_id = c.category_id
                  LEFT JOIN restaurant_master r ON m.restaurant_id = r.restaurant_id
                  WHERE m.isDelete = 1 AND m.menu_status = 1 AND m.category_id = $category_id";
        
        if (!empty($menu_type)) {
            $query .= " AND m.manu_type = '$menu_type'";
        }

        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $menu_items = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $menu_items[] = $row;
                }
                $response['status'] = 200;
                $response['message'] = "Menu items fetched successfully.";
                $response['data'] = $menu_items;
            } else {
                $response['status'] = 201;
                $response['message'] = "No menu items found.";
            }
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
