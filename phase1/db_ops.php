<?php
session_start();
include 'retrieve_data.php';
include 'db_file.php';

// Database connection
$servername = 'localhost';
$username = 'root';
$password = "1234";
$dbname = "web-assignment";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connection = new DBOps($servername, $username, $password, $dbname);
//    echo 1;
//    if(!$connection)

    $data = retrive_post_request();

    $db_response = $connection->insert_query('users',
        $data[0],
        $data[1]);

    if ($db_response['status_code'] != 200) {

        if ($db_response['body'] == 1062) {
            if (strpos($db_response['message'], 'email')) {
                $_SESSION['email_message'] = "Email already taken!";
                header("Location: index.php"); // Redirect back to the registration form
                exit();
            }
        }
        unset($_SESSION['email_message']);
        unset($_SESSION['username_message']);
        exit();
    }


    // File upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["user_image"]["name"]);
    move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file);

    $_SESSION['success_message'] = 'Registration has been made successfully';
    $connection->close_connection();
    header("Location: index.php");
    exit();
}
