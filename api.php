<?php

require 'config.php';

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
    global $conn;

    if ($method === 'GET') {
        if ($id) { 
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) { 
                echo json_encode($user);
            } else {
                echo json_encode(["error"=> "User not found!"]);
            }

        } else {
            $result = $conn->query("SELECT * FROM users");

            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            echo json_encode($users);
        }

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data["name"] ?? null;
        $email = $data["email"] ?? null;

        if (!$name || !$email) {
            echo json_encode(["error"=> "Name and Email required!"]);
            return;
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);

        if ($stmt->execute()) {
            echo json_encode([
                "message"=> "User created!",
                "id"=> $stmt->insert_id
            ]);

        } else {
            echo json_encode([
                "error" => "Failed to create user."
            ]);
        }

    } elseif ($method === 'PUT') {
        if (!$id) {
            echo json_encode(["error"=> "User ID required!"]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $name = trim($data["name"] ?? '');
        $email = trim($data["email"] ?? '');

        if (!$name || !$email) {
            echo json_encode(["error"=> "Name and Email required!"]);
            return;
        }

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $user['id'] = (int)$user['id'];

        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "id"=> $user,
                "name"=> $name,
                "email"=> $email
            ]);

        } else {
            echo json_encode(["error" => "Update failed! User doesn't exist."]);
        }

    } elseif ($method === 'DELETE') {
        if (!$id) {
            echo json_encode(["error"=> "User ID required!"]);
            return;

        } elseif ($id) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) { 
                echo json_encode([
                    "message"=> "User was deleted successfully.",
                    "id"=> $id
                ]);
                
            } else {
                echo json_encode(["error"=> "User doesn't exist!"]);
            }
        }
    }
}

// Products Handler
function handleProducts($method, $id) {
    global $conn;

    if ($method === 'GET') {
        if ($id) { 
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            echo json_encode($product);

            if (!$product) { 
                echo json_encode(["error"=> "No Product found!"]);
            }

        } else {
            $result = $conn->query("SELECT * FROM products");

            $products = [];

            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            echo json_encode($products);
        }

    } elseif ($method === "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $name = $data["name"] ?? null;
        $price = $data["price"] ?? null;

        if (!$name || !$price) {
            echo json_encode(["error"=> "Name and Price required!"]);
            return;
        }

        $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $price);

        if ($stmt->execute()) {
            echo json_encode([
                "message"=> "Product added!",
                "id"=> $stmt->insert_id
            ]);

        } else {
            echo json_encode([
                "error" => "Failed to add product!"
            ]);
        }
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