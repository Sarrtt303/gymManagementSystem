<?php
//headers that will be sent to the client with the response
header("Content-Type: application/json; charset=UTF-8");  //response will be in json
header("Access-Control-Allow-Origin: *"); //allow everyone to access these apis
header("Access-Control-Allow-Headers: Content-Type, Authorization"); //requests with mentioned header are allowed
header("Acess-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS"); //mentioned http requests are allowed

include_once __DIR__ . "/config/database.php";


$db = new Database;  //database instance
$conn = $db->getConnection(); //databse connection instance

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode("?", $_SERVER["REQUEST_URI"], 2); //converts the request uri into an array with two elements 
$path = trim(str_replace('gymManagementSystem/api/v1', '', $request_uri[0]), '/');  //first element, which is the path of the uri
$query_string = $request_uri[1] ?? ""; //second element, the query string

//route handler, distributes requests to appropriate controller according to the path of the uri
switch ($path) {
    case 'users':
        include_once __DIR__ . '/controllers/user.controller.php';
        $user = new User($conn);
        $user->handleRequest($request_method);
        break;

    case 'memberships':
        include_once __DIR__ . '/controllers/membership.controller.php';
        $membership = new Membership($conn);
        $membership->handleRequest($request_method);
        break;

    case 'trainers':
        include_once __DIR__ . '/controllers/trainer.controller.php';
        break;

    case 'classes':
        include_once __DIR__ . '/controllers/class.controller.php';
        break;

    case 'bookings':
        include_once __DIR__ . '/controllers/booking.controller.php';
        break;

    case 'payments':
        include_once __DIR__ . '/controllers/payment.controller.php';
        break;

    case 'attendence':
        include_once __DIR__ . '/controllers/attendence.controller.php';
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
