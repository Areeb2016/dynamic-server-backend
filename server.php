<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// $host = "your_database_host";
// $username = "your_username";
// $password = "your_password";
// $database = "your_database_name";

// // Create a MySQL connection
// $connection = new mysqli($host, $username, $password, $database);

// Check connection
// if ($connection->connect_error) {
//     die("Connection failed: " . $connection->connect_error);
// }

// API endpoint to execute SQL queries
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $host = $data['host'];
    $username = $data['username'];
    $password = $data['password'];
    $database = $data['database'];
    $query = $data['query'];

    // Use the input data to create a new connection
    $dynamicConnection = new mysqli($host, $username, $password, $database);

    // Check dynamic connection
    if ($dynamicConnection->connect_error) {
        die("Dynamic Connection failed: " . $dynamicConnection->connect_error);
    }

    // Execute the SQL query
    $result = $dynamicConnection->query($query);

    if (!$result) {
        $response = ['error' => $dynamicConnection->error];
        http_response_code(500);
    } else {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $response = ['results' => $rows];
    }

    // Close the dynamic connection
    $dynamicConnection->close();

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405); // Method Not Allowed
}

// Close the initial connection
$connection->close();

?>
