<?php

header("Content-Type: application/json");

$request = strtok($_SERVER['REQUEST_URI'], '?');
$parts = explode('/', trim($request, '/'));
$method = $_SERVER['REQUEST_METHOD'];

$resource = $parts[1] ?? null;
$id = $parts[2] ?? null;

// Main Router
switch ($resource) { 
    case 'users':
        handleUsers($method, $id);
        break;

    case 'products':
        handleProducts($method, $id);
        break;

    case 'orders':
        handleOrders($method, $id);
        break;

    default:
        echo json_encode(["error"=> "Route not found!"]);
}

// Users Handler
function handleUsers($method, $id) {

    if ($method === 'GET') {
        if ($id) { 
            echo json_encode([
                "message"=> "Get single user!",
                "id"=> $id
            ]);
        } else {
            echo json_encode([
                "message"=> "Get all users"
            ]);
        }

    } else if ($method === "POST") {
        echo json_encode([
            "message"=> "Create user"
        ]);
    } else {
        echo json_encode([
            "error"=> "Method not allowed!"
        ]);
    }
}

// Products Handler
function handleProducts($method, $id) {
    if ($method === 'GET') {
        if ($id) { 
            echo json_encode([
                "message"=> "Get product!",
                "id"=> $id
            ]);
        } else {
            echo json_encode([
                "message"=> "Get all products"
            ]);
        }

    } elseif ($method === "POST") {
        echo json_encode([
            "message"=> "Add a product"
        ]);
    } else {
        echo json_encode([
            "error"=> "Method not allowed!"
            ]);
    }
}

// Orders Handler
function handleOrders($method, $id) {
    echo json_encode([
        "resource"=> "orders",
        "method"=> $method,
        "id"=> $id
    ]);
}