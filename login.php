<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Predefined users
$users = [
    'usr1' => ['password' => 'password1', 'fullname' => 'Melanie Abalde'],
    'usr2' => ['password' => 'password2', 'fullname' => 'Love Lace'],
    'admin' => ['password' => 'adminpass', 'fullname' => 'Admin User'] 
];

// Output the user data as JSON
echo json_encode($users);
?>