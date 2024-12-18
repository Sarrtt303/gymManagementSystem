<?php

header("Content-Type: application/json; charset=UTF-8");//This tells the client that the server is responding with JSON data.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");

// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . "/config/database.php";


$db = new Database;
$conn = $db->getConnection();

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode("?", $_SERVER["REQUEST_URI"], 2); //converts the request uri into an array with two elements 
$path = trim(str_replace('gymManagementSystem/api/v1', '', $request_uri[0]), '/');  //first element, which is the path of the uri
$query_string = $request_uri[1] ?? ""; //second element, the query string

switch ($path) {
    case 'users':
        include_once __DIR__ . '/src/controllers/user.controller.php';
        $user = new User($conn);
        $user->handleRequest($request_method);
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

    case 'attendance':
        include_once __DIR__ . '/controllers/attendance.controller.php';
        $attendance = new AttendanceController($conn);
        $attendance->handleRequest($request_method);
        break;


    default:
        http_response_code(404);
        echo json_encode(["message" => "Endpoint not found."]);
        echo json_encode(["message" => "Backend functional"]);
        break;
}
