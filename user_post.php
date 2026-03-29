<?php

header("Content-Type: application/json");

$json = file_get_contents("php://input");

$data = json_decode($json, true);

if (!$data) {
    echo json_encode([
        "error"=> "Invalid JSON"
    ]);
    exit;
}

echo json_encode([
    "message"=> "User Created!",
    "recieved"=> $data,
    "raw_data"=> $json
]);