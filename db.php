<?php

$host = "localhost";
$db = "my_api";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "error" => "Database connection failed"
    ]));
}