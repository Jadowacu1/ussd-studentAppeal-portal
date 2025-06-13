<?php
require 'db.php';
require 'ussd_handler.php';

// Check if keys exist to avoid undefined warnings
$sessionId = isset($_POST["sessionId"]) ? $_POST["sessionId"] : "12345";
$phoneNumber = isset($_POST["phoneNumber"]) ? $_POST["phoneNumber"] : "+250700000001";
$text = isset($_POST["text"]) ? $_POST["text"] : "";

// Call handler
$response = handleUssd($text, $phoneNumber, $conn);

header('Content-type: text/plain');
echo $response;
