<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'cart') {
        $errors = [];
        
        if (empty($_POST['user_id'])) {
            $errors['user_id'] = "User ID is required.";
        }
        if (empty($_POST['menu_id'])) {
            $errors['menu_id'] = "Menu ID is required.";
        }
        if (empty($_POST['quantity'])) {
            $errors['quantity'] = "Quantity is required.";
        }
        
        if (!empty($errors)) {
            $response['status'] = 201;
            $response['errors'] = $errors;
            echo json_encode($response);
            exit();
        }

        $user_id = intval($_POST['user_id']);
        $menu_id = intval($_POST['menu_id']);
        $quantity = intval($_POST['quantity']);
        $cart_createdAt = date('Y-m-d H:i:s');

        // Check if the item already exists in the cart
        $check_query = "SELECT cart_id FROM cart_master WHERE user_id = '$user_id' AND menu_id = '$menu_id'";
        $result = mysqli_query($conn, $check_query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Update the quantity with the new value (overwrite)
            $row = mysqli_fetch_assoc($result);
            $update_query = "UPDATE cart_master SET quantity = '$quantity', cart_createdAt = '$cart_createdAt' WHERE cart_id = '{$row['cart_id']}'";

            if (mysqli_query($conn, $update_query)) {
                $response['status'] = 200;
                $response['message'] = "Cart updated successfully.";
            } else {
                $response['status'] = 201;
                $response['message'] = "Database error: " . mysqli_error($conn);
            }
        } else {
            // Insert new cart item
            $insert_query = "INSERT INTO cart_master (user_id, menu_id, quantity, cart_createdAt) VALUES ('$user_id', '$menu_id', '$quantity', '$cart_createdAt')";
            
            if (mysqli_query($conn, $insert_query)) {
                $response['status'] = 200;
                $response['message'] = "Item added to cart successfully.";
            } else {
                $response['status'] = 201;
                $response['message'] = "Database error: " . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['method']) && $_POST['method'] === 'remove') {
        if (empty($_POST['cart_id'])) {
            $response['status'] = 201;
            $response['errors'] = ["cart_id" => "Cart ID is required."];
            echo json_encode($response);
            exit();
        }
        
        $cart_id = intval($_POST['cart_id']);
        $query = "DELETE FROM cart_master WHERE cart_id = '$cart_id'";
        
        if (mysqli_query($conn, $query)) {
            $response['status'] = 200;
            $response['message'] = "Item removed from cart successfully.";
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
