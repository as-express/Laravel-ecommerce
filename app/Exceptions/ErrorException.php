<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    protected $message;
    protected $status;

    public function __construct($message, $status)
    {
        $this->message = $message;
        $this->status = $status;
        parent::__construct($message, $status);
    }

    public function report() {}

    public function throw($request)
    {
        return response()->json([
            'error' => $this->message,
        ], $this->status);
    }
}
