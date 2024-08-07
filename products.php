<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, OPTIONS");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pos_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT barcode, name, price FROM products";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[$row['barcode']] = [
            'name' => $row['name'],
            'price' => $row['price']
        ];
    }
} else {
    echo json_encode(['error' => 'No products found']);
    $conn->close();
    exit();
}

$conn->close();

echo json_encode(['products' => $products]);
?>