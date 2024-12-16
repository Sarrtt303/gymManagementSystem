<?php
include_once "../src/models/attendance.model.php";

try {
    $attendance = new AttendanceSchema($db->conn);

    switch ($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            // Manual attendance input
            $data = json_decode(file_get_contents("php://input"), true);

            // Input validation
            if (!isset($data["member_id"]) || !isset($data["entry_time"])) {
                http_response_code(400);
                echo json_encode([
                    "error" => "Missing required parameters",
                    "required" => ["member_id", "entry_time"]
                ]);
                exit;
            }

            $memberId = $data["member_id"];
            $trainerId = $data["trainer_id"] ?? null;
            $entryTime = $data["entry_time"];
            $exitTime = $data["exit_time"] ?? null;

            try {
                $result = $attendance->addAttendance($memberId, $trainerId, $entryTime, $exitTime);
                
                if ($result) {
                    http_response_code(201);
                    echo json_encode(["message" => "Attendance recorded successfully"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Failed to record attendance"]);
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "error" => "Database error",
                    "details" => $e->getMessage()
                ]);
            }
            break;

        case "GET":
            // Fetch attendance logs
            if (!isset($_GET["member_id"])) {
                http_response_code(400);
                echo json_encode([
                    "error" => "Missing member_id parameter"
                ]);
                exit;
            }

            $memberId = $_GET["member_id"];
            
            try {
                $logs = $attendance->getAttendanceLogs($memberId);
                
                if (empty($logs)) {
                    http_response_code(404);
                    echo json_encode([
                        "message" => "No attendance logs found for this member",
                        "member_id" => $memberId
                    ]);
                } else {
                    echo json_encode($logs);
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "error" => "Database error",
                    "details" => $e->getMessage()
                ]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode([
                "error" => "Method Not Allowed",
                "allowed_methods" => ["GET", "POST"]
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error",
        "details" => $e->getMessage()
    ]);
}