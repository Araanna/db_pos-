<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");

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

// Get product barcode
$barcode = $conn->real_escape_string($data['barcode']);

// Delete SQL query using prepared statements
$sql = "DELETE FROM products WHERE barcode=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $barcode);

// Initialize response
$response = ['success' => false];

// Execute query and set response
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
    } else {
        $response['message'] = "No product found with the given barcode.";
    }
} else {
    $response['message'] = "Error: " . $stmt->error;
}

// Send JSON response
echo json_encode($response);

// Close the statement and connection
$stmt->close();
$conn->close();
?>