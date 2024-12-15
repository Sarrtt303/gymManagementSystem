<?php

include_once "../src/models/attendance.model.php";

$attendance = new AttendanceSchema($db->conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Manual attendance input
    $data = json_decode(file_get_contents("php://input"), true);
    $memberId = $data["member_id"];
    $trainerId = $data["trainer_id"] ?? null;
    $entryTime = $data["entry_time"];
    $exitTime = $data["exit_time"] ?? null;

    if ($attendance->addAttendance($memberId, $trainerId, $entryTime, $exitTime)) {
        echo json_encode(["message" => "Attendance recorded successfully"]);
    } else {
        echo json_encode(["error" => "Failed to record attendance"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Fetch attendance logs
    $memberId = $_GET["member_id"];
    $logs = $attendance->getAttendanceLogs($memberId);
    echo json_encode($logs);
}
