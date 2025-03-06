<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'place_order') {
        $errors = [];
        
        if (empty($_POST['user_id'])) {
            $errors['user_id'] = "User ID is required.";
        }
        
        if (!empty($errors)) {
            $response['status'] = 201;
            $response['errors'] = $errors;
            echo json_encode($response);
            exit();
        }

        $user_id = intval($_POST['user_id']);
        $order_createdAt = date('Y-m-d H:i:s');

        // Fetch restaurant_id and total_amount dynamically
        $cart_query = "SELECT c.cart_id, c.menu_id, c.quantity, m.restaurant_id, m.menu_price 
                       FROM cart_master c 
                       JOIN menu_master m ON c.menu_id = m.menu_id 
                       WHERE c.user_id = '$user_id'";
        
        $cart_result = mysqli_query($conn, $cart_query);
        
        if (mysqli_num_rows($cart_result) > 0) {
            $restaurant_id = null;
            $total_amount = 0;
            $cart_items = [];
            
            while ($row = mysqli_fetch_assoc($cart_result)) {
                if ($restaurant_id === null) {
                    $restaurant_id = intval($row['restaurant_id']);
                } elseif ($restaurant_id !== intval($row['restaurant_id'])) {
                    $response['status'] = 201;
                    $response['message'] = "All cart items must belong to the same restaurant.";
                    echo json_encode($response);
                    exit();
                }
                $total_amount += floatval($row['menu_price']) * intval($row['quantity']);
                $cart_items[] = $row;
            }

            // Insert into order_master
            $query = "INSERT INTO order_master (user_id, restaurant_id, total_amount, order_status, order_createdAt, order_updatedAt) 
                      VALUES ('$user_id', '$restaurant_id', '$total_amount', '0', '$order_createdAt', '$order_createdAt')";
            
            if (mysqli_query($conn, $query)) {
                $order_id = mysqli_insert_id($conn);
                $order_item_inserted = true;
                $cart_ids = [];

                // Insert into order_item_master
                foreach ($cart_items as $item) {
                    $menu_id = intval($item['menu_id']);
                    $quantity = intval($item['quantity']);
                    $order_item_price = floatval($item['menu_price']) * $quantity;
                    $order_item_createdAt = date('Y-m-d H:i:s');

                    $order_item_query = "INSERT INTO order_item_master (order_id, menu_id, quantity, order_item_price, order_item_createdAt) 
                                        VALUES ('$order_id', '$menu_id', '$quantity', '$order_item_price', '$order_item_createdAt')";
                    
                    if (!mysqli_query($conn, $order_item_query)) {
                        $order_item_inserted = false;
                        break;
                    }
                    $cart_ids[] = intval($item['cart_id']);
                }

                if ($order_item_inserted) {
                    if (!empty($cart_ids)) {
                        $cart_ids_str = implode(',', $cart_ids);
                        $delete_cart_query = "DELETE FROM cart_master WHERE cart_id IN ($cart_ids_str)";
                        mysqli_query($conn, $delete_cart_query);
                    }

                    $response['status'] = 200;
                    $response['message'] = "Order placed successfully.";
                    $response['order_id'] = $order_id;
                } else {
                    $response['status'] = 201;
                    $response['message'] = "Error inserting order items.";
                }
            } else {
                $response['status'] = 201;
                $response['message'] = "Database error: " . mysqli_error($conn);
            }
        } else {
            $response['status'] = 201;
            $response['message'] = "No items found in cart.";
        }
    } elseif (isset($_POST['method']) && $_POST['method'] === 'remove_order') {
        if (empty($_POST['order_id'])) {
            $response['status'] = 201;
            $response['errors'] = ["order_id" => "Order ID is required."];
            echo json_encode($response);
            exit();
        }

        $order_id = intval($_POST['order_id']);

        $update_order_query = "UPDATE order_master SET order_status = '3' WHERE order_id = '$order_id'";
        
        if (mysqli_query($conn, $update_order_query)) {
            $response['status'] = 200;
            $response['message'] = "Order cancelled successfully.";
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