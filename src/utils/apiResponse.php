<?php

class ApiResponse
{
    public $code;
    public $message;
    public $data;

    public function __construct($code, $message, $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $response = [
            "code" => $code,
            "message" => $message,
            "data" => $data
        ];

        http_response_code($code);
        echo json_encode($response);
    }
}