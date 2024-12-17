<?php
include_once __DIR__ . "/../models/attendence.model.php";
include_once __DIR__ . "/../models/class.model.php";
include_once __DIR__ . "/../models/user.model.php";
include_once __DIR__ . "/../models/trainer.model.php";
include_once __DIR__ . "/../models/membership.model.php";
include_once __DIR__ . "/../utils/apiResponse.php";
include_once __DIR__ . "/../utils/apiError.php";

class Attendence
{

    private $attendenceSchema;
    private $classSchema;
    private $trainerSchema;
    private $userSchema;
    private $membershipSchema;

    public function __construct($db)
    {
        $this->attendenceSchema = new AttendenceSchema($db);
        $this->classSchema = new ClassSchema($db);
        $this->trainerSchema = new TrainerSchema($db);
        $this->userSchema = new UserSchema($db);
        $this->membershipSchema = new MembershipSchema($db);
        $this->initializeDatabase();
    }

    public function initializeDatabase()
    {
        $this->membershipSchema->createMembershipsTable();
        $this->userSchema->createUsersTable();
        $this->trainerSchema->createTrainersTable();
        $this->classSchema->createClassesTable();
        $this->attendenceSchema->createAttendencesTable();
        $this->attendenceSchema->createAttendencesTable();
    }

    public function handleRequest($request_method)
    {
        switch ($request_method) {
            case 'GET':
                # code...
                break;

            case 'POST':
                break;

            case 'PATCH':
                break;

            case 'DELETE':
                break;

            default:
                # code...
                break;
        }
    }

    //write functions here..
}
