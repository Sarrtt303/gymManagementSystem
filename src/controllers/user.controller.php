<?php
include_once __DIR__ . '/../utils/apiResponse.php';
include_once __DIR__ . "/../utils/apiError.php";
include_once __DIR__ . "/../models/user.model.php";
include_once __DIR__ . "/../models/membership.model.php";
class User
{

    private $user;
    private $userSchema;
    private $membershipSchema;

    public function __construct($db)
    {
        $this->userSchema = new UserSchema($db);
        $this->membershipSchema = new MembershipSchema($db);

        $this->initializeDatabase();
    }

    //it will create all the necessary tables before processing the request
    private function initializeDatabase()
    {
        $this->membershipSchema->createMembershipsTable();
        $this->userSchema->createUsersTable();
    }

    public function handleRequest($request_method)
    {
        switch ($request_method) {
            case 'GET':
                $this->getUser();
                break;

            case 'POST':
                $this->registerUser();
                break;

            case 'PATCH':
                if (isset($_GET['user-details'])) {
                    $this->updateUserDetails();
                } elseif (isset($_GET['user-password'])) {
                    $this->updateUserPassword();
                } elseif (isset($_GET['update-role'])) {
                    $this->updateUserRole();
                }
                break;

            case 'DELETE':
                $this->deleteUser();
                break;

            default:
                echo 'request received';
                break;
        }
    }
    private function registerUser()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!empty($data['name']) && !empty($data['email']) && !empty($data['password']) && !empty($data['role']) && !empty($data['phone'])) {
                $createdUser = $this->user->create($data);
                if ($createdUser) {
                    new apiResponse(201, "User registered successfully", $createdUser);
                } else {
                    new ApiError(500, "user registeration failed");
                }
            } else {
                new apiError(400, "incomplete data provide");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function getUser()
    {
        try {
            if ($_GET["id"]) {
                $id = $_GET["id"];
                $user = $this->userSchema->read($id);
                if ($user) {
                    new ApiResponse(200, "user fetched successfully", $user);
                } else {
                    new ApiError(400, "user not found");
                }
            } else {
                echo "user id is required";
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function updateUserDetails()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data) {
                    $updatedUser = $this->userSchema->updateDetails($id, $data);
                    if ($updatedUser) {
                        new apiResponse(200, "user details updated successfully", $updatedUser);
                    } else {
                        new ApiError(400, "user not found");
                    }
                } else {
                    echo "user data is required";
                }
            } else {
                echo "user id is required";
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function deleteUser()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $user = $this->userSchema->delete($id);
                if ($user) {
                    new apiResponse(200, "user deleted successfully", $user);
                } else {
                    new apiError(400, "user not found");
                }
            } else {
                new ApiError(400, "user id is required");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function updateUserPassword()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data) {
                    $user = $this->userSchema->updatePassword($id, $data);
                    if ($user) {
                        new apiResponse(200, "user password updated successfully", $user);
                    } else {
                        new apiError(400, "user not found");
                    }
                } else {
                    new apiError(400, "user password is missing");
                }
            } else {
                new apiError(400, "user id is required");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    private function updateUserRole()
    {
        try {
            $id = $_GET["id"];
            if ($id) {
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data) {
                    $user = $this->userSchema->updateRole($id, $data);
                    if ($user) {
                        new apiResponse(200, "user role updated successfully", $user);
                    } else {
                        new apiError(400, "user not found");
                    }
                } else {
                    new apiError(400, "user data is required");
                }
            } else {
                new apiError(400, "user id is required");
            }
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }
}
