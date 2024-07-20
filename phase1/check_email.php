<?php
// Assuming session_start() is called at the beginning if you are using sessions
//session_start();
include 'db_file.php'; // Ensure you have a file that handles your database connection

$servername = 'localhost';
$username = 'root';
$password = "1234";
$dbname = "web-assignment";

$connection = new DBOps($servername, $username, $password, $dbname);

$email = $_POST['email'] ?? '';
//$email = 'hobaaaa';

if (!empty($email)) {
    // Prepare a statement for execution
    $result = $connection->select_query("users", 'email', $email);

    if ($result["status_code"] == 200) {
        echo json_encode(['status' => 'exists', 'message' => 'Email is already registered.']);
    } else {
        echo json_encode(['status' => 'not_exists', 'message' => 'Email is available.']);
    }
} else {
    echo "Error"; // Appropriate error handling (e.g., $email not provided)
}
$connection->close_connection();