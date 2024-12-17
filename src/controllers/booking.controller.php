<?php
include_once __DIR__ . "/../models/booking.model.php";
include_once __DIR__ . "/../models/class.model.php";
include_once __DIR__ . "/../models/user.model.php";
include_once __DIR__ . "/../models/trainer.model.php";
include_once __DIR__ . "/../models/membership.model.php";
include_once __DIR__ . "/../utils/apiResponse.php";
include_once __DIR__ . "/../utils/apiError.php";

class Booking
{

    private $bookingSchema;
    private $classSchema;
    private $trainerSchema;
    private $userSchema;
    private $membershipSchema;

    public function __construct($db)
    {
        $this->bookingSchema = new BookingSchema($db);
        $this->classSchema = new ClassSchema($db);
        $this->trainerSchema = new TrainerSchema($db);
        $this->userSchema = new UserSchema($db);
        $this->membershipSchema = new MembershipSchema($db);
        $this->initializeDatabase();
    }

    private function initializeDatabase()
    {
        $this->membershipSchema->createMembershipsTable();
        $this->userSchema->createUsersTable();
        $this->trainerSchema->createTrainersTable();
        $this->classSchema->createClassesTable();
        $this->bookingSchema->createBookingsTable();
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
