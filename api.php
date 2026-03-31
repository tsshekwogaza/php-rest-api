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

    case 'register':
        handleRegister();
        break;

    case 'login':
        handleLogin();
        break;

    default:
        echo json_encode(["error"=> "Route not found!"]);
}

// Users Handler
function handleUsers($method, $id) {
    global $conn;

    $token = getBearerToken();

    if (!$token) {
        echo json_encode(["error" => "Unauthorized!"]);
        return;
    }

    // check token in DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $result = $stmt->get_result();
    $authUser = $result->fetch_assoc();

    if (!$authUser) {
        echo json_encode(["error"=> "Invalid token"]);
        return;
    }

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
        $name = trim($data["name"] ?? '');
        $email = trim($data["email"] ?? '');

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

        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "id"=> (int)$id,
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

// Register handler
function handleRegister() {
    global $conn;
        
    $data = json_decode(file_get_contents("php://input"), true);

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        echo json_encode(["error" => "Invalid input"]);
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["message"=> "User registered!"]);
    } else {
        echo json_encode(["error"=> "Registeration failed!"]);
    }
}

// Login handler
function handleLogin() {
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);

    $email = trim($data["email"] ?? '');
    $password = $data["password"] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($password, $user["password"])) {
        echo json_encode(["error"=> "Invalid credentials!"]);
        return;
    }

    // Generate token
    $token = bin2hex(random_bytes(16));

    // Store token in DB
    $stmt = $conn->prepare("UPDATE users SET token = ? WHERE id = ?");
    $stmt->bind_param("si", $token, $user['id']);
    $stmt->execute();

    echo json_encode([
        "message"=> "Login successfull.",
        "token"=> $token
    ]);
}

function getBearerToken() {
    $headers = getallheaders();

    if (isset($headers['Authorization'])) {
        return str_replace('Bearer ','', $headers['Authorization']);
    }

    return null;
}