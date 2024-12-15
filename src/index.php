<?php

header("Content-Type: application/json; charset=UTF-8");//This tells the client that the server is responding with JSON data.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Acess-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");

include_once "../src/config/database.php";
include_once "../src/models/user.model.php"; 

$db = new Database;
$user = new UserSchema($db->conn);
$user->createUserTable();

$request_uri = explode("?", $_SERVER["REQUEST_URI"], 2);
$path = trim($request_uri[0], "/");
$query_string = $request_uri[1] ?? "";

switch ($path) {
    case 'api/v1/users':
        include_once '../src/controllers/user.controller.php';
        break;

    case 'api/v1/memberships':
        include_once '../src/controllers/membership.controller.php';
        break;

    case 'api/v1/trainers':
        include_once '../src/controllers/trainer.controller.php';
        break;

    case 'api/v1/classes':
        include_once '../src/controllers/class.controller.php';
        break;

    case 'api/v1/bookings':
        include_once '../src/controllers/booking.controller.php';
        break;

    case 'api/v1/payments':
        include_once '../src/controllers/payment.controller.php';
        break;

    case 'api/v1/attendance':
        include_once '../src/controllers/attendance.controller.php';
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
