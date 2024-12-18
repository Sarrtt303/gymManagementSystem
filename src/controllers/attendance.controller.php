<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/attendance.model.php';

class AttendanceController {
    private $conn;
    private $attendanceModel;

    public function __construct($db) {
        $this->conn = $db;
        $this->attendanceModel = new AttendanceSchema($db);
    }

    // Main method to handle different request methods
    public function handleRequest($method) {
        switch ($method) {
            case 'POST':
                $this->handlePost();
                break;
            case 'GET':
                $this->handleGet();
                break;
            default:
                $this->sendResponse(405, ['error' => 'Method Not Allowed']);
                break;
        }
    }

    // Handle POST requests for attendance
    private function handlePost() {
        // Parse input data
        $input = json_decode(file_get_contents('php://input'), true);

        // Determine the specific action based on the request
        if (isset($input['action'])) {
            switch ($input['action']) {
                case 'record_entry':
                    $this->recordEntry($input);
                    break;
                case 'record_exit':
                    $this->recordExit($input);
                    break;
                default:
                    $this->sendResponse(400, ['error' => 'Invalid action']);
            }
        } else {
            $this->sendResponse(400, ['error' => 'Action not specified']);
        }
    }

    // Handle GET requests for attendance
    private function handleGet() {
        // Get query parameters
        $memberId = $_GET['member_id'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $action = $_GET['action'] ?? null;

        if (!$memberId) {
            $this->sendResponse(400, ['error' => 'Member ID is required']);
            return;
        }

        switch ($action) {
            case 'logs':
                $this->getAttendanceLogs($memberId, $startDate, $endDate);
                break;
            case 'summary':
                $this->getAttendanceSummary($memberId, $startDate, $endDate);
                break;
            default:
                $this->sendResponse(400, ['error' => 'Invalid action']);
        }
    }

    // Record entry method
    private function recordEntry($input) {
        try {
            // Validate required fields
            if (!isset($input['member_id'])) {
                $this->sendResponse(400, ['error' => 'Member ID is required']);
                return;
            }

            $memberId = $input['member_id'];
            $trainerId = $input['trainer_id'] ?? null;
            $entryTime = date('Y-m-d H:i:s');

            // Use the AttendanceSchema method to add attendance
            $result = $this->attendanceModel->addAttendance($memberId, $trainerId, $entryTime);

            if ($result) {
                $this->sendResponse(201, [
                    'status' => 'success',
                    'message' => 'Entry recorded successfully',
                    'entry_time' => $entryTime
                ]);
            } else {
                $this->sendResponse(500, ['error' => 'Failed to record entry']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // Record exit method
    private function recordExit($input) {
        try {
            // Note: This method might need modification based on the exact implementation 
            // of the AttendanceSchema. The current implementation doesn't have a specific 
            // method for recording exit separately.
            
            // For now, we'll use the same addAttendance method, but with an exit time
            if (!isset($input['member_id'])) {
                $this->sendResponse(400, ['error' => 'Member ID is required']);
                return;
            }

            $memberId = $input['member_id'];
            $exitTime = date('Y-m-d H:i:s');

            // Find the last entry without an exit time and update it
            $query = "UPDATE attendance 
                      SET exit_time = ? 
                      WHERE member_id = ? 
                      AND exit_time IS NULL 
                      ORDER BY entry_time DESC 
                      LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$exitTime, $memberId]);

            if ($result && $stmt->rowCount() > 0) {
                $this->sendResponse(200, [
                    'status' => 'success',
                    'message' => 'Exit recorded successfully',
                    'exit_time' => $exitTime
                ]);
            } else {
                $this->sendResponse(404, ['error' => 'No active entry found for this member']);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // Get attendance logs
    private function getAttendanceLogs($memberId, $startDate = null, $endDate = null) {
        try {
            // Use the AttendanceSchema method to get logs
            $logs = $this->attendanceModel->getAttendanceLogs($memberId, $startDate, $endDate);

            $this->sendResponse(200, [
                'status' => 'success',
                'logs' => $logs
            ]);
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // Get attendance summary
    private function getAttendanceSummary($memberId, $startDate = null, $endDate = null) {
        try {
            // Get logs first
            $logs = $this->attendanceModel->getAttendanceLogs($memberId, $startDate, $endDate);

            // Calculate summary metrics
            $totalVisits = count($logs);
            $totalDuration = 0;
            
            foreach ($logs as $log) {
                if ($log['exit_time']) {
                    $entryTime = strtotime($log['entry_time']);
                    $exitTime = strtotime($log['exit_time']);
                    $totalDuration += $exitTime - $entryTime;
                }
            }

            $this->sendResponse(200, [
                'status' => 'success',
                'total_visits' => $totalVisits,
                'total_duration' => gmdate('H:i:s', $totalDuration)
            ]);
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => $e->getMessage()]);
        }
    }

    // Utility method to send JSON response
    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}