<?php

header('Content-Type: application/json');

$request = strtok($_SERVER['REQUEST_URI'], '?');
$parts = explode('/', trim($request, '/'));
$method = $_SERVER['REQUEST_METHOD'];

$resource = $parts[1] ?? null;
$id = $parts[2] ?? null;

if ($resource === 'users') {
    if ($method === 'GET') {
        if ($id) {
            echo json_encode([
                "message"=> "Get single user",
                "id"=> $id
            ]);
        } else {
            echo json_encode([
                "message"=> "Get all users"
            ]);
        }
    } elseif ($method === "POST") {
        $data = json_decode(file_get_contents("php://input"), true);

        echo json_encode([
            "message"=> "Create user",
            "data"=> $data
        ]);
    }   
    
// Logic for products 
} elseif ($resource === 'products') {
    echo json_encode([
        "message"=> "Products route works!",
    ]);

} else {
    echo json_encode([
        "message"=> "Invalid endpoint"
    ]);
}