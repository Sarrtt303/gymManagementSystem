<?php

include_once __DIR__ . "/../models/membership.model.php";
include_once __DIR__ . "/../utils/apiResponse.php";
include_once __DIR__ . "/../utils/apiError.php";

class Membership
{

    private $membershipSchema;

    public function __construct($db)
    {
        $this->membershipSchema = new MembershipSchema($db);
        $this->inititalizeDatabase();
    }

    private function inititalizeDatabase()
    {
        $this->membershipSchema->createMembershipsTable();
    }

    public function handleRequest($request_method)
    {
        switch ($request_method) {
            case 'GET':
                if (isset($_GET['get-membership'])) {
                    $this->fetchMembership();
                } else if (isset($_GET['get-all-memberships'])) {
                    $this->fetchAllMemberships();
                }
                break;

            case 'POST':
                $this->createMembership();
                break;

            case 'PATCH':
                $this->updateMembership();
                break;

            case 'DELETE':
                $this->deleteMembership();
                break;

            default:
                header("HTTP/1.0 405 Method Not Allowed");
                break;
        }
    }

    private function createMembership()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data["name"]) && isset($data["price"]) && isset($data["duration"]) && isset($data["description"])) {
                $membership = $this->membershipSchema->create($data);
                if ($membership) {
                    new ApiResponse("201", "user created successfully", $membership);
                } else {
                    new ApiError("400", "user already exists");
                }
            } else {
                new ApiError("400", "incomplete data provided");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function fetchMembership()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $membership = $this->membershipSchema->read($id);
                if ($membership) {
                    new ApiResponse("201", "membership fetched successfully", $membership);
                } else {
                    new ApiError("400", "membership not found");
                }
            } else {
                new ApiError("400", "membership id is required");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function fetchAllMemberships()
    {
        try {
            $memberships = $this->membershipSchema->readAll();
            if ($memberships) {
                new ApiResponse("201", "memberships fetched successfully", $memberships);
            } else {
                new ApiError("400", "no memberships exists");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function updateMembership()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data) {
                    $membership = $this->membershipSchema->update($id, $data);
                    if ($membership) {
                        new ApiResponse("201", "membership updated successfully", $membership);
                    } else {
                        new ApiError("400", "membership not found");
                    }
                } else {
                    new ApiError("400", "data not found");
                }
            } else {
                new ApiError("400", "membership id is required");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function deleteMembership()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $membership = $this->membershipSchema->delete($id);
                if ($membership) {
                    new ApiResponse("20", "membership deleted successfully", $membership);
                } else {
                    new ApiError("400", "membership not found");
                }
            } else {
                new ApiError("400", "membership id is required");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }
}
