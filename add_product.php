<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Handle OPTIONS method (CORS preflight check)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(); // End preflight request here
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pos_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

// Get product details
$barcode = $conn->real_escape_string($data['barcode']);
$name = $conn->real_escape_string($data['name']);
$price = $conn->real_escape_string($data['price']);

// Insert SQL query using prepared statements
$sql = "INSERT INTO products (barcode, name, price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $barcode, $name, $price);

// Initialize response
$response = ['success' => false];

// Execute query and set response
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['message'] = "Error: " . $stmt->error;
}

// Send JSON response
echo json_encode($response);

// Close the statement and connection
$stmt->close();
$conn->close();
?>