<?php 

header("Content-Type: application/json");

$json = file_get_contents("php://input");

$data = json_decode($json, true);

if (!$data) {
    echo json_encode([
        "error message"=> "Kindly provide feedback!",
    ]);
    exit;
}

$name = $data["name"] ?? null;
$message = $data["message"] ?? null;

if (!$name || !$message) {
    echo json_encode([
        "error"=> "Name and Message required!",
    ]);
    exit;
}

echo json_encode([
    "status"=> "recieved",
    "feedback" => [
        "name"=> $name,
        "message"=> $message,
    ]
]);