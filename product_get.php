<?php

header("Content-Type: application/json");

$id = $_GET["id"] ?? null;
$location = $_GET["location"] ?? null;

if ($id === null) {
    echo json_encode(["error" => "Missing 'id' parameter!"]);
    exit;
}

if ($location === null) {
    echo json_encode(["error" => "Missing 'location' parameter!"]);
    exit;
}

$product = [
    "id"=> $id,
    "name"=> "Product $id",
    "price"=> rand(10, 100),
    "location" => $location
];

echo json_encode($product);