<?php

header("Content-Type: application/json");

$users = $_GET['name'] ?? null;

if ($users === null) {
    echo json_encode([
        "error" => "Missing 'name' parameter"
    ]);
    exit;
};

$user = [
    "id" => "00050",
    "Username" => $users,
    "email" => "$users@gmail.com",
    "role" => "member"
];

echo json_encode($user);