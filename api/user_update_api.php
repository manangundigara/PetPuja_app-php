<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../lib/connection.php';
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['method']) && $_POST['method'] === 'update') {

        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

        if ($user_id === 0) {
            $response['message'] = "User ID is required.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Check if user exists
        $check_user = mysqli_query($conn, "SELECT * FROM user_master WHERE user_id = $user_id");
        if (mysqli_num_rows($check_user) == 0) {
            $response['message'] = "User not found.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        $user_data = mysqli_fetch_assoc($check_user);

        // If user is blocked (is_delete = 0), show friendly message
        if ($user_data['is_delete'] == 0) {
            $response['message'] = "Your account is restricted. Please contact support.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        $update_fields = [];
        $valid_update = false; // Flag to track if any valid update is given

        if (!empty($_POST['user_name'])) {
            $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
            $update_fields[] = "user_name = '$user_name'";
            $valid_update = true;
        }

        if (!empty($_POST['user_email'])) {
            $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $response['message'] = "Invalid email format.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }
            $update_fields[] = "user_email = '$user_email'";
            $valid_update = true;
        }

        if (!empty($_POST['user_phone'])) {
            $user_phone = mysqli_real_escape_string($conn, $_POST['user_phone']);
            if (!preg_match("/^[0-9]{10}$/", $user_phone)) {
                $response['message'] = "Phone number must be exactly 10 digits.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }
            $update_fields[] = "user_phone = '$user_phone'";
            $valid_update = true;
        }

        if (!empty($_POST['user_gender'])) {
            $user_gender = intval($_POST['user_gender']);
            if (!in_array($user_gender, [1, 2, 3])) {
                $response['message'] = "Invalid gender value.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }
            $update_fields[] = "user_gender = $user_gender";
            $valid_update = true;
        }

        // Adding User Address Update
        if (!empty($_POST['user_address'])) {
            $user_address = mysqli_real_escape_string($conn, $_POST['user_address']);
            $update_fields[] = "user_address = '$user_address'";
            $valid_update = true;
        }

        // Prevent password updates
        if (!empty($_POST['user_password'])) {
            $response['message'] = "Password updates are not allowed.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        // Handle Image Upload
        if (isset($_FILES['user_img']) && $_FILES['user_img']['error'] === 0) {
            $target_dir = "../uploads/profile/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $original_name = pathinfo($_FILES["user_img"]["name"], PATHINFO_FILENAME);
            $imageFileType = strtolower(pathinfo($_FILES["user_img"]["name"], PATHINFO_EXTENSION));

            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowed_types)) {
                $response['message'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF allowed.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            // Generate filename: `user_id_filename.extension`
            $clean_name = preg_replace("/[^a-zA-Z0-9]/", "", $original_name);
            $new_file_name = "user_" . $user_id . "_" . $clean_name . "." . $imageFileType;
            $target_file = $target_dir . $new_file_name;

            // Check file size (limit: 5MB)
            if ($_FILES["user_img"]["size"] > 5 * 1024 * 1024) {
                $response['message'] = "File size exceeds 5MB limit.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }

            if (move_uploaded_file($_FILES["user_img"]["tmp_name"], $target_file)) {
                $update_fields[] = "user_img = '$new_file_name'"; // Store only filename
                $valid_update = true;
            } else {
                $response['message'] = "Failed to upload image.";
                $response['status'] = 201;
                echo json_encode($response);
                exit();
            }
        }

        if (!empty($_POST['update_by'])) {
            $update_by = intval($_POST['update_by']);
            $update_fields[] = "update_by = $update_by";
            $valid_update = true;
        }

        if (!$valid_update) {
            $response['message'] = "No valid fields provided for update.";
            $response['status'] = 201;
            echo json_encode($response);
            exit();
        }

        $update_fields[] = "updated_date = CURRENT_TIMESTAMP()";

        $query = "UPDATE user_master SET " . implode(", ", $update_fields) . " WHERE user_id = $user_id";

        if (mysqli_query($conn, $query)) {
            $response['status'] = 200;
            $response['message'] = "User details updated successfully!";

            if (isset($new_file_name)) {
                $image_url = "http://192.168.37.31/Mutli-Restaurant-Food-Order/uploads/profile/" . $new_file_name;
                $response['image_filename'] = $new_file_name;
                $response['image_url'] = $image_url;
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



// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// include '../lib/connection.php';
// $response = [];

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (isset($_POST['method'])) {
//         if ($_POST['method'] === 'update') {

//             if (isset($_POST['user_id'])) {
//                 $user_id = intval($_POST['user_id']);
//             } else {
//                 $user_id = 0;
//             }

//             if ($user_id === 0) {
//                 $response['message'] = "User ID is required.";
//                 $response['status'] = 201;
//                 echo json_encode($response);
//                 exit();
//             }

//             // Check if user exists
//             $check_user = mysqli_query($conn, "SELECT * FROM user_master WHERE user_id = $user_id");
//             if (mysqli_num_rows($check_user) == 0) {
//                 $response['message'] = "User not found.";
//                 $response['status'] = 201;
//                 echo json_encode($response);
//                 exit();
//             }

//             $user_data = mysqli_fetch_assoc($check_user);

//             // If user is blocked (is_delete = 0), show friendly message
//             if ($user_data['is_delete'] == 0) {
//                 $response['message'] = "Your account is currently restricted due to policy violations or security reasons. Please contact support for assistance.";
//                 $response['status'] = 201;
//                 echo json_encode($response);
//                 exit();
//             }

//             $update_fields = [];
//             $valid_update = false; // Flag to track if any valid update is given

//             if (isset($_POST['user_name']) && !empty($_POST['user_name'])) {
//                 $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
//                 $update_fields[] = "user_name = '$user_name'";
//                 $valid_update = true;
//             }

//             if (isset($_POST['user_email']) && !empty($_POST['user_email'])) {
//                 $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
//                 if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
//                     $response['message'] = "Invalid email format.";
//                     $response['status'] = 201;
//                     echo json_encode($response);
//                     exit();
//                 }
//                 $update_fields[] = "user_email = '$user_email'";
//                 $valid_update = true;
//             }

//             if (isset($_POST['user_phone']) && !empty($_POST['user_phone'])) {
//                 $user_phone = mysqli_real_escape_string($conn, $_POST['user_phone']);
//                 if (!preg_match("/^[0-9]{10}$/", $user_phone)) {
//                     $response['message'] = "Phone number must be exactly 10 digits.";
//                     $response['status'] = 201;
//                     echo json_encode($response);
//                     exit();
//                 }
//                 $update_fields[] = "user_phone = '$user_phone'";
//                 $valid_update = true;
//             }

//             if (isset($_POST['user_gender']) && !empty($_POST['user_gender'])) {
//                 $user_gender = intval($_POST['user_gender']);
//                 if (!in_array($user_gender, [1, 2, 3])) {
//                     $response['message'] = "Invalid gender value.";
//                     $response['status'] = 201;
//                     echo json_encode($response);
//                     exit();
//                 }
//                 $update_fields[] = "user_gender = $user_gender";
//                 $valid_update = true;
//             }

//             // Prevent password updates
//             if (isset($_POST['user_password']) && !empty($_POST['user_password'])) {
//                 $response['message'] = "Password updates are not allowed.";
//                 $response['status'] = 201;
//                 echo json_encode($response);
//                 exit();
//             }

//             // Handle Image Upload (Store Only `user_id_filename.extension`)
//             if (isset($_FILES['user_img']) && $_FILES['user_img']['error'] === 0) {
//                 $target_dir = "../uploads/profile/";
//                 if (!is_dir($target_dir)) {
//                     mkdir($target_dir, 0777, true);
//                 }

//                 $original_name = pathinfo($_FILES["user_img"]["name"], PATHINFO_FILENAME);
//                 $imageFileType = strtolower(pathinfo($_FILES["user_img"]["name"], PATHINFO_EXTENSION));

//                 $allowed_types = ["jpg", "jpeg", "png", "gif"];
//                 if (!in_array($imageFileType, $allowed_types)) {
//                     $response['message'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF allowed.";
//                     $response['status'] = 201;
//                     echo json_encode($response);
//                     exit();
//                 }

//                 // Generate filename: `user_id_filename.extension`
//                 $clean_name = preg_replace("/[^a-zA-Z0-9]/", "", $original_name);
//                 $new_file_name = "user_" . $user_id . "_" . $clean_name . "." . $imageFileType;
//                 $target_file = $target_dir . $new_file_name;

//                 // Check file size (limit: 5MB)
//                 if ($_FILES["user_img"]["size"] > 5 * 1024 * 1024) {
//                     $response['message'] = "File size exceeds 5MB limit.";
//                     $response['status'] = 201;
//                     echo json_encode($response);
//                     exit();
//                 }

//                 if (move_uploaded_file($_FILES["user_img"]["tmp_name"], $target_file)) {
//                     $update_fields[] = "user_img = '$new_file_name'"; // Store only filename
//                     $valid_update = true;
//                 } else {
//                     $response['message'] = "Failed to upload image.";
//                     $response['status'] = 201;
//                     echo json_encode($response);
//                     exit();
//                 }
//             }

//             if (isset($_POST['update_by']) && !empty($_POST['update_by'])) {
//                 $update_by = intval($_POST['update_by']);
//                 $update_fields[] = "update_by = $update_by";
//                 $valid_update = true;
//             }

//             if (!$valid_update) {
//                 $response['message'] = "No valid fields provided for update.";
//                 $response['status'] = 201;
//                 echo json_encode($response);
//                 exit();
//             }

//             $update_fields[] = "updated_date = CURRENT_TIMESTAMP()";

//             $query = "UPDATE user_master SET " . implode(", ", $update_fields) . " WHERE user_id = $user_id";

//             if (mysqli_query($conn, $query)) {
//                 $response['status'] = 200;
//                 $response['message'] = "User details updated successfully!";

//                 if (isset($new_file_name)) {
//                     $image_url = "http://192.168.37.31/Mutli-Restaurant-Food-Order/uploads/profile/" . $new_file_name;
//                     $response['image_filename'] = $new_file_name;
//                     $response['image_url'] = $image_url;
//                 }
//             } else {
//                 $response['status'] = 201;
//                 $response['message'] = "Database error: " . mysqli_error($conn);
//             }

//         } else {
//             $response['message'] = "Invalid method token.";
//             $response['status'] = 201;
//         }
//     } else {
//         $response['message'] = "Method is required.";
//         $response['status'] = 201;
//     }
// } else {
//     $response['message'] = "Only POST method is allowed.";
//     $response['status'] = 201;
// }

// echo json_encode($response);

?>