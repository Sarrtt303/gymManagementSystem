<?php
include_once __DIR__ . "/../models/user.model.php";
include_once __DIR__ . "/../models/membership.model.php";
include_once __DIR__ . "/../models/payment.model.php";
include_once __DIR__ . "/../utils/apiResponse.php";
include_once __DIR__ . "/../utils/apiError.php";

class Payment
{
    private $paymentSchema;
    private $userSchema;
    private $membershipSchema;

    public function __construct($db)
    {
        $this->membershipSchema = new MembershipSchema($db);
        $this->userSchema = new UserSchema($db);
        $this->paymentSchema = new PaymentSchema($db);

        $this->initializeDatabase();
    }

    private function initializeDatabase()
    {
        $this->membershipSchema->createMembershipsTable();
        $this->userSchema->createUsersTable();
        $this->paymentSchema->createPaymentsTable();
    }

    public function handleRequest($request_method)
    {
        switch ($$request_method) {
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
