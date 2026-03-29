<?php

header("Content-Type: application/json");

$code = [
    "time"=> date("Y-m-d H:i:s"),
    "ip"=> $_SERVER['REMOTE_ADDR'],
    "message"=> "Welcome to my API!"
];

echo json_encode($code);