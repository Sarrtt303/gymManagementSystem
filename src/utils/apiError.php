<?php

class ApiError
{
    public $code;
    public $message;

    public function __construct($code = 500, $message = "Internal server error")
    {
        $this->code = $code;
        $this->message = $message;

        $response = [
            "code" => $code,
            "message" => $message
        ];

        echo json_encode($response);
    }
}