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
        $this->user = new UserSchema($db);
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
                # code...
                break;

            case 'POST':
                $this->registerUser();
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
}
