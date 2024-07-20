<?php
// Assuming session_start() is called at the beginning if you are using sessions
//session_start();
include 'db_file.php'; // Ensure you have a file that handles your database connection

$servername = 'localhost';
$username = 'root';
$password = "1234";
$dbname = "web-assignment";

$connection = new DBOps($servername, $username, $password, $dbname);

$username = $_POST['username'] ?? '';
//$username = 'hobaaaa';

if (!empty($username)) {
    // Prepare a statement for execution
    $result = $connection->select_query("users", 'username', $username);

    if ($result["status_code"] == 200) {
        echo "403"; // Username is not available
    } else {
        echo "200"; // Username is available
    }
} else {
    echo "Error"; // Appropriate error handling (e.g., username not provided)
}
$connection->close_connection();