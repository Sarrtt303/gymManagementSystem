<?php

include_once "../src/models/booking.model.php";

$booking = new BookingSchema($db->conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Book a session
    $data = json_decode(file_get_contents("php://input"), true);
    $sessionId = $data["session_id"];
    $memberId = $data["member_id"];

    if ($booking->bookSession($sessionId, $memberId)) {
        echo json_encode(["message" => "Session booked successfully"]);
    } else {
        echo json_encode(["error" => "Failed to book session"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Fetch bookings by session
    $sessionId = $_GET["session_id"];
    $bookings = $booking->getBookingsBySession($sessionId);
    echo json_encode($bookings);
} elseif ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    // Cancel a booking
    $data = json_decode(file_get_contents("php://input"), true);
    $bookingId = $data["booking_id"];

    if ($booking->cancelBooking($bookingId)) {
        echo json_encode(["message" => "Booking cancelled successfully"]);
    } else {
        echo json_encode(["error" => "Failed to cancel booking"]);
    }
}
